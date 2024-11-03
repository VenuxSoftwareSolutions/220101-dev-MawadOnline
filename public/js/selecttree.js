(function ($) {

    $.fn.select2tree = function (options) {
        var defaults = {
            language: "pt-BR",
            theme: "select2",
            matcher: matchCustom,
            templateSelection: templateSelectionCustom,
            templateResult: templateResultCustom
        };
        var opts = $.extend(defaults, options);
        var $this = $(this);
        $(this).select2(opts).on("select2:open", function () {
            open($this);
        });
    };

    function showSpinner($parent) {
        $parent.css('position', 'relative').prepend('<div class="spinner-overlay"><div class="spinner-border"></div></div>');
    }

    // Function to hide spinner
    function hideSpinner($parent) {
        $parent.find('.spinner-overlay').remove();
    }
    
    function templateResultCustom(data, container) {
        if (data.element) {
            var $wrapper = $("<span></span><span>" + data.text + "</span>");
            var $element = $(data.element);
            var $select = $element.parent();
            var $container = $(container);

            $container.attr("val", $element.val());
            $container.attr("data-parent", $element.data("parent"));

            var hasChilds = $select.find("option[data-parent='" + $element.val() + "']").length > 0;
            var isSearching = $(".select2-search__field").val().length > 0;

            // Highlight matching part
            var term = $(".select2-search__field").val().toLowerCase();
            var text = data.text;
            if (term && text.toLowerCase().indexOf(term) > -1) {
                // Escape special HTML characters in term to avoid XSS
                var escapedTerm = term.replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, "\\$&");
                var regex = new RegExp("(" + escapedTerm + ")", "gi");
                text = text.replace(regex, "<span style='background-color: yellow;'>$1</span>");
                $wrapper = $("<span></span><span>" + text + "</span>");
            }

            if (isSearching) {
                $wrapper.first().css({
                    "padding": "0 10px 0 10px",
                });
            } else if (hasChilds) {
                var $switchSpn = $wrapper.first();
                $switchSpn.addClass("switch-tree glyphicon");
                if ($switchSpn.hasClass("las la-plus"))
                    $switchSpn.removeClass("las la-plus").addClass("las la-minus");
                else
                    $switchSpn.removeClass("las la-minus").addClass("las la-plus");

                $switchSpn.css({
                    "padding": "0 10px 0 10px",
                    "cursor": "pointer"
                });
            }

            if (hasParent($element)) {
                var paddingLeft = getTreeLevel($select, $element.val()) * 2;
                if (!hasChilds) paddingLeft++;
                $container.css("margin-left", paddingLeft + "em");
            }

            return $wrapper;
        } else {
            return data.text;
        }
    };

 
    
    function hasParent($element) {
        return $element.data("parent") !== '';
    }

    function getTreeLevel($select, id) {
        var level = 0;
        while ($select.find("option[data-parent!=''][value='" + id + "']").length > 0) {
            id = $select.find("option[value='" + id + "']").data("parent");
            level++;
        }
        return level;
    }


    function moveOption($select, id) {
        if (id) {
            $select.find(".select2-results__options li[data-parent='" + id + "']").insertAfter(".select2-results__options li[val=" + id + "]");
            $select.find(".select2-results__options li[data-parent='" + id + "']").each(function () {
                moveOption($select, $(this).attr("val"));
            });
        } else {

            $(".select2-results__options li[data-parent!='']").css("display", "none");
            $(".select2-results__options li[data-parent='']").appendTo(".select2-results__options ul");
            $(".select2-results__options li[data-parent='']").each(function () {
                moveOption($select, $(this).attr("val"));
            });
        }
    }

    function switchAction($select, id, open) {

        var childs = $(".select2-results__options li[data-parent='" + id + "']");
        //expand childs.
        //childs.each(function() {
        //  switchAction($select, $(this).attr("val"), open);
        //});

        var parent = $(".select2-results__options li[val=" + id + "] span[class]:eq(0)");
        if (open) {
            parent.removeClass("las la-plus")
                .addClass("las la-minus");
            childs.slideDown();
        } else {
            parent.removeClass("las la-minus")
                .addClass("las la-plus");
            childs.slideUp();
        }
    }

    function open($select) {
        setTimeout(function () {

            moveOption($select);
            //override mousedown for collapse/expand 
            $(".switch-tree").mousedown(function () {
                switchAction($select, $(this).parent().attr("val"), $(this).hasClass("las la-plus"));
                event.stopPropagation();
            });
            //override mouseup to nothing
            $(".switch-tree").mouseup(function () {
                return false;
            });

        }, 0);
    }

    function matchCustom(params, data) {
        if ($.trim(params.term).length < 3) {
            // Display all options if no search term is entered
            if ($.trim(params.term) === '') {
                return data;
            }
            // Do not return any result if the term length is less than 3
            return null;
        }

        if (typeof data.text === 'undefined') {
            return null;
        }
        var term = params.term.toLowerCase();
        var $element = $(data.element);
        var $select = $element.parent();
        var childMatched = checkForChildMatch($select, $element, term);
        if (childMatched || data.text.toLowerCase().indexOf(term) >= 0) {
            $("#" + data._resultId).css("display", "unset");
            return data;
        }
        return null;
    }

    function checkForChildMatch($select, $element, term) {
        var matched = false;
        var childs = $select.find('option[data-parent=' + $element.val() + ']');
        var childMatchFilter = jQuery.makeArray(childs).some(s => s.text.toLowerCase().indexOf(term) >= 0)
        if (childMatchFilter) return true;

        childs.each(function () {
            var innerChild = checkForChildMatch($select, $(this), term);
            if (innerChild) matched = true;
        });

        return matched;
    }

    function templateSelectionCustom(item) {
        if (!item.id || item.id == "-1") {
            return $("<i class='fa fa-hand-o-right'></i><span> " + item.text + "</span>");
        }

        var $element = $(item.element);
        var $select = $element.parent();

        var parentsText = getParentText($select, $element);
        if (parentsText != '') parentsText += ' - ';

        var $state = $(
            "<span> " + parentsText + item.text + "</span>"
        );
        return $state;
    }

    function getParentText($select, $element) {
        var text = '';
        var parentVal = $element.data('parent');
        if (parentVal == '') return text;

        var parent = $select.find('option[value=' + parentVal + ']');

        if (parent) {
            text = getParentText($select, parent);
            if (text != '') text += ' - ';
            text += parent.text();
        }
        return text;
    }
})(jQuery);

///USAGE
//  $("#tree1").select2tree();

$("#tree1").on("select2:open", function (e) {
    console.log("select2:open", e);
});
$("#tree1").on("select2:close", function (e) {
    console.log("select2:close", e);
});
$("#tree1").on("select2:select", function (e) {
    console.log("select2:select", e);
});
$("#tree1").on("select2:unselect", function (e) {
    console.log("select2:unselect", e);
});
