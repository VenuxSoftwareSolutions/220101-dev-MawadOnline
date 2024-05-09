@if(count($catalogs) > 0)
    <div class="row">
        @foreach($catalogs as $catalog)
            <div class="col-3">
                <div class="card" style="width: 18rem; height: 400px">
                    <div class="card-header py-2">
                        <div class="d-flex justify-content-center">
                            <div class="p-2" style="background: #dddddd;">
                                <img class="card-img-top p-2" src="{{ asset('/public'.$catalog->getFirstImage()) }}" style="height: 200px;">
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $catalog->sku }}</h5>
                        <p class="card-text">Number of variants: {{ $catalog->checkIfParentToGetNumVariants() }}.</p>

                        <a href="{{ route('catalog.preview_product', ['id' => $catalog->id, 'is_catalog' => 1]) }}" class="btn btn-primary" style="position: absolute; bottom: 20px !important; left: 50%; transform: translateX(-50%); width: 90%">{{ translate('View product') }}</a>
                    </div>
                </div>
            </div>
        @endforeach

        {{-- {{ $catalogs->onEachSide(1)->links('seller.product.catalog.pagination') }} --}}
    </div>
    <hr>
    <div class="row">
        <div class="col-6" style="padding-top: 11px; !important">
            <p style="color: #bababa">Showing {{ $catalogs->firstItem() }} - {{ $catalogs->lastItem() }} of {{ $catalogs->total() }}</p>
        </div>
        <div class="col-6" style="display: flex; justify-content: flex-end;">
            {{ $catalogs->links() }}
        </div>
    </div>
@else
    <div class="text-center">
        {{translate('No catalog found') }}
    </div>
@endif
