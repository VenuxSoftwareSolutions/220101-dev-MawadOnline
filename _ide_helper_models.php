<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\Addon
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $unique_identifier
 * @property string|null $version
 * @property int $activated
 * @property string|null $image
 * @property string|null $purchase_code
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Addon newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Addon newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Addon query()
 * @method static \Illuminate\Database\Eloquent\Builder|Addon whereActivated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Addon whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Addon whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Addon whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Addon whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Addon wherePurchaseCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Addon whereUniqueIdentifier($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Addon whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Addon whereVersion($value)
 */
	class Addon extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Address
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $address
 * @property int|null $country_id
 * @property int $state_id
 * @property int|null $city_id
 * @property float|null $longitude
 * @property float|null $latitude
 * @property string|null $postal_code
 * @property string|null $phone
 * @property int $set_default
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\City|null $city
 * @property-read \App\Models\Country|null $country
 * @property-read \App\Models\State|null $state
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Address newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Address newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Address query()
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address wherePostalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereSetDefault($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereStateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereUserId($value)
 */
	class Address extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\AffiliateConfig
 *
 * @property int $id
 * @property string|null $type
 * @property string|null $value
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|AffiliateConfig newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AffiliateConfig newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AffiliateConfig query()
 * @method static \Illuminate\Database\Eloquent\Builder|AffiliateConfig whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AffiliateConfig whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AffiliateConfig whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AffiliateConfig whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AffiliateConfig whereValue($value)
 */
	class AffiliateConfig extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\AffiliateEarningDetail
 *
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|AffiliateEarningDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AffiliateEarningDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AffiliateEarningDetail query()
 */
	class AffiliateEarningDetail extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\AffiliateLog
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $guest_id
 * @property int $referred_by_user
 * @property float $amount
 * @property int|null $order_id
 * @property int|null $order_detail_id
 * @property string $affiliate_type
 * @property int $status
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\Order|null $order
 * @property-read \App\Models\OrderDetail|null $order_detail
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|AffiliateLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AffiliateLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AffiliateLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|AffiliateLog whereAffiliateType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AffiliateLog whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AffiliateLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AffiliateLog whereGuestId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AffiliateLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AffiliateLog whereOrderDetailId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AffiliateLog whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AffiliateLog whereReferredByUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AffiliateLog whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AffiliateLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AffiliateLog whereUserId($value)
 */
	class AffiliateLog extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\AffiliateOption
 *
 * @property int $id
 * @property string|null $type
 * @property string|null $details
 * @property float $percentage
 * @property int $status
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|AffiliateOption newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AffiliateOption newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AffiliateOption query()
 * @method static \Illuminate\Database\Eloquent\Builder|AffiliateOption whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AffiliateOption whereDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AffiliateOption whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AffiliateOption wherePercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AffiliateOption whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AffiliateOption whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AffiliateOption whereUpdatedAt($value)
 */
	class AffiliateOption extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\AffiliatePayment
 *
 * @property int $id
 * @property int $affiliate_user_id
 * @property float $amount
 * @property string $payment_method
 * @property string|null $payment_details
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|AffiliatePayment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AffiliatePayment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AffiliatePayment query()
 * @method static \Illuminate\Database\Eloquent\Builder|AffiliatePayment whereAffiliateUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AffiliatePayment whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AffiliatePayment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AffiliatePayment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AffiliatePayment wherePaymentDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AffiliatePayment wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AffiliatePayment whereUpdatedAt($value)
 */
	class AffiliatePayment extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\AffiliateStats
 *
 * @property int $id
 * @property int $affiliate_user_id
 * @property int $no_of_click
 * @property int $no_of_order_item
 * @property int $no_of_delivered
 * @property int $no_of_cancel
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|AffiliateStats newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AffiliateStats newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AffiliateStats query()
 * @method static \Illuminate\Database\Eloquent\Builder|AffiliateStats whereAffiliateUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AffiliateStats whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AffiliateStats whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AffiliateStats whereNoOfCancel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AffiliateStats whereNoOfClick($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AffiliateStats whereNoOfDelivered($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AffiliateStats whereNoOfOrderItem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AffiliateStats whereUpdatedAt($value)
 */
	class AffiliateStats extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\AffiliateUser
 *
 * @property int $id
 * @property string|null $paypal_email
 * @property string|null $bank_information
 * @property int $user_id
 * @property string|null $informations
 * @property float $balance
 * @property int $status
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|AffiliateUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AffiliateUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AffiliateUser query()
 * @method static \Illuminate\Database\Eloquent\Builder|AffiliateUser whereBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AffiliateUser whereBankInformation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AffiliateUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AffiliateUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AffiliateUser whereInformations($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AffiliateUser wherePaypalEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AffiliateUser whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AffiliateUser whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AffiliateUser whereUserId($value)
 */
	class AffiliateUser extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\AffiliateWithdrawRequest
 *
 * @property int $id
 * @property int $user_id
 * @property float $amount
 * @property int $status
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|AffiliateWithdrawRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AffiliateWithdrawRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AffiliateWithdrawRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder|AffiliateWithdrawRequest whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AffiliateWithdrawRequest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AffiliateWithdrawRequest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AffiliateWithdrawRequest whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AffiliateWithdrawRequest whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AffiliateWithdrawRequest whereUserId($value)
 */
	class AffiliateWithdrawRequest extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\AppSettings
 *
 * @property-read \App\Models\Currency|null $currency
 * @method static \Illuminate\Database\Eloquent\Builder|AppSettings newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AppSettings newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AppSettings query()
 */
	class AppSettings extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\AppTranslation
 *
 * @property int $id
 * @property string|null $lang
 * @property string|null $lang_key
 * @property string|null $lang_value
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|AppTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AppTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AppTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|AppTranslation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppTranslation whereLang($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppTranslation whereLangKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppTranslation whereLangValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppTranslation whereUpdatedAt($value)
 */
	class AppTranslation extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Area
 *
 * @property int $id
 * @property array $name
 * @property int $emirate_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $translations
 * @method static \Illuminate\Database\Eloquent\Builder|Area newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Area newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Area query()
 * @method static \Illuminate\Database\Eloquent\Builder|Area whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Area whereEmirateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Area whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Area whereJsonContainsLocale(string $column, string $locale, ?mixed $value)
 * @method static \Illuminate\Database\Eloquent\Builder|Area whereJsonContainsLocales(string $column, array $locales, ?mixed $value)
 * @method static \Illuminate\Database\Eloquent\Builder|Area whereLocale(string $column, string $locale)
 * @method static \Illuminate\Database\Eloquent\Builder|Area whereLocales(string $column, array $locales)
 * @method static \Illuminate\Database\Eloquent\Builder|Area whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Area whereUpdatedAt($value)
 */
	class Area extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Attribute
 *
 * @property int $id
 * @property string|null $name
 * @property string $name_display_english
 * @property string $name_display_arabic
 * @property string $type_value
 * @property string|null $description_english
 * @property string|null $description_arabic
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property int $is_activated
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AttributeTranslation> $attribute_translations
 * @property-read int|null $attribute_translations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AttributeValue> $attribute_values
 * @property-read int|null $attribute_values_count
 * @method static \Illuminate\Database\Eloquent\Builder|Attribute newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Attribute newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Attribute query()
 * @method static \Illuminate\Database\Eloquent\Builder|Attribute whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attribute whereDescriptionArabic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attribute whereDescriptionEnglish($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attribute whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attribute whereIsActivated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attribute whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attribute whereNameDisplayArabic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attribute whereNameDisplayEnglish($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attribute whereTypeValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attribute whereUpdatedAt($value)
 */
	class Attribute extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\AttributeCategory
 *
 * @property int $id
 * @property int $category_id
 * @property int $attribute_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeCategory whereAttributeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeCategory whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeCategory whereUpdatedAt($value)
 */
	class AttributeCategory extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\AttributeTranslation
 *
 * @property int $id
 * @property int $attribute_id
 * @property string $name
 * @property string $lang
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\Attribute|null $attribute
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeTranslation whereAttributeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeTranslation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeTranslation whereLang($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeTranslation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeTranslation whereUpdatedAt($value)
 */
	class AttributeTranslation extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\AttributeValue
 *
 * @property int $id
 * @property int $attribute_id
 * @property array $value
 * @property string|null $color_code
 * @property string|null $lang
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\Attribute|null $attribute
 * @property-read mixed $translations
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeValue newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeValue newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeValue query()
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeValue whereAttributeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeValue whereColorCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeValue whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeValue whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeValue whereJsonContainsLocale(string $column, string $locale, ?mixed $value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeValue whereJsonContainsLocales(string $column, array $locales, ?mixed $value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeValue whereLang($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeValue whereLocale(string $column, string $locale)
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeValue whereLocales(string $column, array $locales)
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeValue whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeValue whereValue($value)
 */
	class AttributeValue extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\AttributesUnity
 *
 * @property int $id
 * @property int $unite_id
 * @property int $attribute_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|AttributesUnity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AttributesUnity newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AttributesUnity query()
 * @method static \Illuminate\Database\Eloquent\Builder|AttributesUnity whereAttributeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttributesUnity whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttributesUnity whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttributesUnity whereUniteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttributesUnity whereUpdatedAt($value)
 */
	class AttributesUnity extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\AuctionProductBid
 *
 * @property-read \App\Models\Product|null $product
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|AuctionProductBid newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AuctionProductBid newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AuctionProductBid query()
 */
	class AuctionProductBid extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Banner
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Banner newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Banner newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Banner query()
 */
	class Banner extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Blog
 *
 * @property int $id
 * @property int $category_id
 * @property string $title
 * @property string $slug
 * @property string|null $short_description
 * @property string|null $description
 * @property int|null $banner
 * @property string|null $meta_title
 * @property int|null $meta_img
 * @property string|null $meta_description
 * @property string|null $meta_keywords
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\BlogCategory|null $category
 * @method static \Illuminate\Database\Eloquent\Builder|Blog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Blog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Blog onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Blog query()
 * @method static \Illuminate\Database\Eloquent\Builder|Blog whereBanner($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blog whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blog whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blog whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blog whereMetaDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blog whereMetaImg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blog whereMetaKeywords($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blog whereMetaTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blog whereShortDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blog whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blog whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blog whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blog withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Blog withoutTrashed()
 */
	class Blog extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\BlogCategory
 *
 * @property int $id
 * @property string $category_name
 * @property string $slug
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Blog> $posts
 * @property-read int|null $posts_count
 * @method static \Illuminate\Database\Eloquent\Builder|BlogCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BlogCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BlogCategory onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|BlogCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|BlogCategory whereCategoryName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BlogCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BlogCategory whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BlogCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BlogCategory whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BlogCategory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BlogCategory withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|BlogCategory withoutTrashed()
 */
	class BlogCategory extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Brand
 *
 * @property int $id
 * @property string $name
 * @property string|null $logo
 * @property int $top
 * @property string|null $slug
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\Upload|null $brandLogo
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BrandTranslation> $brand_translations
 * @property-read int|null $brand_translations_count
 * @method static \Illuminate\Database\Eloquent\Builder|Brand newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Brand newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Brand query()
 * @method static \Illuminate\Database\Eloquent\Builder|Brand whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Brand whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Brand whereLogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Brand whereMetaDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Brand whereMetaTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Brand whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Brand whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Brand whereTop($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Brand whereUpdatedAt($value)
 */
	class Brand extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\BrandTranslation
 *
 * @property int $id
 * @property int $brand_id
 * @property string $name
 * @property string $lang
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\Brand|null $brand
 * @method static \Illuminate\Database\Eloquent\Builder|BrandTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BrandTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BrandTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|BrandTranslation whereBrandId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BrandTranslation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BrandTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BrandTranslation whereLang($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BrandTranslation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BrandTranslation whereUpdatedAt($value)
 */
	class BrandTranslation extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\BulkUploadFile
 *
 * @method static \Illuminate\Database\Eloquent\Builder|BulkUploadFile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BulkUploadFile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BulkUploadFile query()
 */
	class BulkUploadFile extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\BusinessInformation
 *
 * @property int $id
 * @property int $user_id
 * @property array|null $trade_name
 * @property string|null $trade_license_doc
 * @property array|null $eshop_name
 * @property array|null $eshop_desc
 * @property string|null $license_issue_date
 * @property string|null $license_expiry_date
 * @property int|null $state
 * @property int|null $area_id
 * @property string|null $street
 * @property string|null $building
 * @property string|null $unit
 * @property string|null $po_box
 * @property string|null $landline
 * @property int|null $vat_registered
 * @property string|null $vat_certificate
 * @property string|null $trn
 * @property string|null $tax_waiver
 * @property string|null $civil_defense_approval
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $saveasdraft Save as Draft
 * @property-read mixed $translations
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessInformation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessInformation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessInformation query()
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessInformation whereAreaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessInformation whereBuilding($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessInformation whereCivilDefenseApproval($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessInformation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessInformation whereEshopDesc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessInformation whereEshopName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessInformation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessInformation whereJsonContainsLocale(string $column, string $locale, ?mixed $value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessInformation whereJsonContainsLocales(string $column, array $locales, ?mixed $value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessInformation whereLandline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessInformation whereLicenseExpiryDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessInformation whereLicenseIssueDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessInformation whereLocale(string $column, string $locale)
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessInformation whereLocales(string $column, array $locales)
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessInformation wherePoBox($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessInformation whereSaveasdraft($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessInformation whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessInformation whereStreet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessInformation whereTaxWaiver($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessInformation whereTradeLicenseDoc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessInformation whereTradeName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessInformation whereTrn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessInformation whereUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessInformation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessInformation whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessInformation whereVatCertificate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessInformation whereVatRegistered($value)
 */
	class BusinessInformation extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\BusinessSetting
 *
 * @property int $id
 * @property string $type
 * @property string|null $value
 * @property string|null $lang
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessSetting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessSetting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessSetting query()
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessSetting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessSetting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessSetting whereLang($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessSetting whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessSetting whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessSetting whereValue($value)
 */
	class BusinessSetting extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Carrier
 *
 * @property int $id
 * @property string $name
 * @property int|null $logo
 * @property string $transit_time
 * @property int $free_shipping
 * @property int $status
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CarrierRangePrice> $carrier_range_prices
 * @property-read int|null $carrier_range_prices_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CarrierRange> $carrier_ranges
 * @property-read int|null $carrier_ranges_count
 * @method static \Illuminate\Database\Eloquent\Builder|Carrier active()
 * @method static \Illuminate\Database\Eloquent\Builder|Carrier newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Carrier newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Carrier query()
 * @method static \Illuminate\Database\Eloquent\Builder|Carrier whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carrier whereFreeShipping($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carrier whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carrier whereLogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carrier whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carrier whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carrier whereTransitTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carrier whereUpdatedAt($value)
 */
	class Carrier extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\CarrierRange
 *
 * @property int $id
 * @property int $carrier_id
 * @property string $billing_type
 * @property float $delimiter1
 * @property float $delimiter2
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\Carrier|null $carrier
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CarrierRangePrice> $carrier_range_prices
 * @property-read int|null $carrier_range_prices_count
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierRange newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierRange newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierRange query()
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierRange whereBillingType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierRange whereCarrierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierRange whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierRange whereDelimiter1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierRange whereDelimiter2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierRange whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierRange whereUpdatedAt($value)
 */
	class CarrierRange extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\CarrierRangePrice
 *
 * @property int $id
 * @property int $carrier_id
 * @property int $carrier_range_id
 * @property int $zone_id
 * @property float $price
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\Carrier|null $carrier
 * @property-read \App\Models\CarrierRange|null $carrier_ranges
 * @property-read \App\Models\Zone|null $zone
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierRangePrice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierRangePrice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierRangePrice query()
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierRangePrice whereCarrierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierRangePrice whereCarrierRangeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierRangePrice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierRangePrice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierRangePrice wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierRangePrice whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierRangePrice whereZoneId($value)
 */
	class CarrierRangePrice extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Cart
 *
 * @property int $id
 * @property int|null $owner_id
 * @property int|null $user_id
 * @property string|null $temp_user_id
 * @property int $address_id
 * @property int|null $product_id
 * @property string|null $variation
 * @property float|null $price
 * @property float|null $tax
 * @property float $shipping_cost
 * @property string $shipping_type
 * @property int|null $pickup_point
 * @property int|null $carrier_id
 * @property float $discount
 * @property string|null $product_referral_code
 * @property string|null $coupon_code
 * @property int $coupon_applied
 * @property int $quantity
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Address|null $address
 * @property-read \App\Models\Product|null $product
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Cart newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Cart newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Cart query()
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereAddressId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereCarrierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereCouponApplied($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereCouponCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart wherePickupPoint($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereProductReferralCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereShippingCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereShippingType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereTempUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereVariation($value)
 */
	class Cart extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\CartProduct
 *
 * @property-read \App\Models\Product|null $product
 * @method static \Illuminate\Database\Eloquent\Builder|CartProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CartProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CartProduct query()
 */
	class CartProduct extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Category
 *
 * @property int $id
 * @property int|null $parent_id
 * @property int $level
 * @property string $name
 * @property int $order_level
 * @property float $commision_rate
 * @property string|null $thumbnail_image
 * @property string|null $icon
 * @property string|null $cover_image
 * @property int $featured
 * @property int $top
 * @property int $digital
 * @property string|null $slug
 * @property string|null $description
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Attribute> $attributes
 * @property-read int|null $attributes_count
 * @property-read \App\Models\Upload|null $bannerImage
 * @property-read \App\Models\Upload|null $catIcon
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Category> $categories
 * @property-read int|null $categories_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Attribute> $categories_attributes
 * @property-read int|null $categories_attributes_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CategoryTranslation> $category_translations
 * @property-read int|null $category_translations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Category> $childrenCategories
 * @property-read int|null $children_categories_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CustomerProduct> $classified_products
 * @property-read int|null $classified_products_count
 * @property-read \App\Models\Upload|null $coverImage
 * @property-read Category|null $parent
 * @property-read Category|null $parentCategory
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product> $products
 * @property-read int|null $products_count
 * @property-read \App\Models\SizeChart|null $sizeChart
 * @method static \Illuminate\Database\Eloquent\Builder|Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category query()
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereCommisionRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereCoverImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereDigital($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereFeatured($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereMetaDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereMetaTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereOrderLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereThumbnailImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereTop($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereUpdatedAt($value)
 */
	class Category extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\CategoryTranslation
 *
 * @property int $id
 * @property int $category_id
 * @property string $name
 * @property string|null $description
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property string $lang
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\Category|null $category
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryTranslation whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryTranslation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryTranslation whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryTranslation whereLang($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryTranslation whereMetaDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryTranslation whereMetaTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryTranslation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryTranslation whereUpdatedAt($value)
 */
	class CategoryTranslation extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\City
 *
 * @property int $id
 * @property string $name
 * @property int $state_id
 * @property float $cost
 * @property int $status
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property string|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CityTranslation> $city_translations
 * @property-read int|null $city_translations_count
 * @property-read \App\Models\Country|null $country
 * @property-read \App\Models\State|null $state
 * @method static \Illuminate\Database\Eloquent\Builder|City newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|City newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|City query()
 * @method static \Illuminate\Database\Eloquent\Builder|City whereCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereStateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereUpdatedAt($value)
 */
	class City extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\CityTranslation
 *
 * @property int $id
 * @property int $city_id
 * @property string $name
 * @property string $lang
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\City|null $city
 * @method static \Illuminate\Database\Eloquent\Builder|CityTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CityTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CityTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|CityTranslation whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CityTranslation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CityTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CityTranslation whereLang($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CityTranslation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CityTranslation whereUpdatedAt($value)
 */
	class CityTranslation extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ClubPoint
 *
 * @property int $id
 * @property int $user_id
 * @property float $points
 * @property int $order_id
 * @property int $convert_status
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ClubPointDetail> $club_point_details
 * @property-read int|null $club_point_details_count
 * @property-read \App\Models\Order|null $order
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|ClubPoint newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ClubPoint newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ClubPoint query()
 * @method static \Illuminate\Database\Eloquent\Builder|ClubPoint whereConvertStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClubPoint whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClubPoint whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClubPoint whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClubPoint wherePoints($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClubPoint whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClubPoint whereUserId($value)
 */
	class ClubPoint extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ClubPointDetail
 *
 * @property int $id
 * @property int $club_point_id
 * @property int $product_id
 * @property int $product_qty
 * @property float $point
 * @property float|null $converted_amount
 * @property int $refunded
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\ClubPoint|null $club_point
 * @property-read \App\Models\Product|null $product
 * @method static \Illuminate\Database\Eloquent\Builder|ClubPointDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ClubPointDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ClubPointDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder|ClubPointDetail whereClubPointId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClubPointDetail whereConvertedAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClubPointDetail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClubPointDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClubPointDetail wherePoint($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClubPointDetail whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClubPointDetail whereProductQty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClubPointDetail whereRefunded($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClubPointDetail whereUpdatedAt($value)
 */
	class ClubPointDetail extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Color
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $code
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Color newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Color newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Color query()
 * @method static \Illuminate\Database\Eloquent\Builder|Color whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Color whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Color whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Color whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Color whereUpdatedAt($value)
 */
	class Color extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\CombinedOrder
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $shipping_address
 * @property float $grand_total
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Order> $orders
 * @property-read int|null $orders_count
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|CombinedOrder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CombinedOrder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CombinedOrder query()
 * @method static \Illuminate\Database\Eloquent\Builder|CombinedOrder whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CombinedOrder whereGrandTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CombinedOrder whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CombinedOrder whereShippingAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CombinedOrder whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CombinedOrder whereUserId($value)
 */
	class CombinedOrder extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\CommissionHistory
 *
 * @property int $id
 * @property int $order_id
 * @property int $order_detail_id
 * @property int $seller_id
 * @property float $admin_commission
 * @property float $seller_earning
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\Order|null $order
 * @method static \Illuminate\Database\Eloquent\Builder|CommissionHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CommissionHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CommissionHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder|CommissionHistory whereAdminCommission($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommissionHistory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommissionHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommissionHistory whereOrderDetailId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommissionHistory whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommissionHistory whereSellerEarning($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommissionHistory whereSellerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommissionHistory whereUpdatedAt($value)
 */
	class CommissionHistory extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ContactPerson
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $email
 * @property string|null $mobile_phone
 * @property string|null $additional_mobile_phone
 * @property string|null $nationality
 * @property string|null $date_of_birth
 * @property string|null $emirates_id_number
 * @property string|null $emirates_id_expiry_date
 * @property string|null $emirates_id_file_path
 * @property int|null $business_owner
 * @property string|null $designation
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $saveasdraft
 * @method static \Illuminate\Database\Eloquent\Builder|ContactPerson newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ContactPerson newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ContactPerson query()
 * @method static \Illuminate\Database\Eloquent\Builder|ContactPerson whereAdditionalMobilePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContactPerson whereBusinessOwner($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContactPerson whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContactPerson whereDateOfBirth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContactPerson whereDesignation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContactPerson whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContactPerson whereEmiratesIdExpiryDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContactPerson whereEmiratesIdFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContactPerson whereEmiratesIdNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContactPerson whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContactPerson whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContactPerson whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContactPerson whereMobilePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContactPerson whereNationality($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContactPerson whereSaveasdraft($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContactPerson whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContactPerson whereUserId($value)
 */
	class ContactPerson extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Conversation
 *
 * @property int $id
 * @property int $sender_id
 * @property int $receiver_id
 * @property string|null $title
 * @property int $sender_viewed
 * @property int $receiver_viewed
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Message> $messages
 * @property-read int|null $messages_count
 * @property-read \App\Models\User|null $receiver
 * @property-read \App\Models\User|null $sender
 * @method static \Illuminate\Database\Eloquent\Builder|Conversation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Conversation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Conversation query()
 * @method static \Illuminate\Database\Eloquent\Builder|Conversation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conversation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conversation whereReceiverId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conversation whereReceiverViewed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conversation whereSenderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conversation whereSenderViewed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conversation whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conversation whereUpdatedAt($value)
 */
	class Conversation extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Country
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property int $zone_id
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \App\Models\Zone|null $zone
 * @method static \Illuminate\Database\Eloquent\Builder|Country isEnabled()
 * @method static \Illuminate\Database\Eloquent\Builder|Country newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Country newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Country query()
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereZoneId($value)
 */
	class Country extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Coupon
 *
 * @property int $id
 * @property int $user_id
 * @property string $type
 * @property string $code
 * @property string $details
 * @property float $discount
 * @property string $discount_type
 * @property int|null $start_date
 * @property int|null $end_date
 * @property int $status
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CouponUsage> $couponUsages
 * @property-read int|null $coupon_usages_count
 * @property-read \App\Models\User|null $user
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\UserCoupon> $userCoupons
 * @property-read int|null $user_coupons_count
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon query()
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereDiscountType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereUserId($value)
 */
	class Coupon extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\CouponUsage
 *
 * @property int $id
 * @property int $user_id
 * @property int $coupon_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|CouponUsage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CouponUsage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CouponUsage query()
 * @method static \Illuminate\Database\Eloquent\Builder|CouponUsage whereCouponId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CouponUsage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CouponUsage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CouponUsage whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CouponUsage whereUserId($value)
 */
	class CouponUsage extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Currency
 *
 * @property int $id
 * @property string $name
 * @property string $symbol
 * @property float $exchange_rate
 * @property int $status
 * @property string|null $code
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Currency newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Currency newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Currency query()
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereExchangeRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereSymbol($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereUpdatedAt($value)
 */
	class Currency extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Customer
 *
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Customer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Customer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Customer query()
 */
	class Customer extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\CustomerPackage
 *
 * @property int $id
 * @property string|null $name
 * @property float|null $amount
 * @property int|null $product_upload
 * @property string|null $logo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CustomerPackagePayment> $customer_package_payments
 * @property-read int|null $customer_package_payments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CustomerPackageTranslation> $customer_package_translations
 * @property-read int|null $customer_package_translations_count
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPackage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPackage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPackage query()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPackage whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPackage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPackage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPackage whereLogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPackage whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPackage whereProductUpload($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPackage whereUpdatedAt($value)
 */
	class CustomerPackage extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\CustomerPackagePayment
 *
 * @property int $id
 * @property int $user_id
 * @property int $customer_package_id
 * @property string $payment_method
 * @property string $payment_details
 * @property int $approval
 * @property int $offline_payment 1=offline payment
 * 2=online paymnet
 * @property string $reciept
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\CustomerPackage|null $customer_package
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPackagePayment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPackagePayment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPackagePayment query()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPackagePayment whereApproval($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPackagePayment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPackagePayment whereCustomerPackageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPackagePayment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPackagePayment whereOfflinePayment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPackagePayment wherePaymentDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPackagePayment wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPackagePayment whereReciept($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPackagePayment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPackagePayment whereUserId($value)
 */
	class CustomerPackagePayment extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\CustomerPackageTranslation
 *
 * @property int $id
 * @property int $customer_package_id
 * @property string $name
 * @property string $lang
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\CustomerPackage|null $customer_package
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPackageTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPackageTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPackageTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPackageTranslation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPackageTranslation whereCustomerPackageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPackageTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPackageTranslation whereLang($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPackageTranslation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPackageTranslation whereUpdatedAt($value)
 */
	class CustomerPackageTranslation extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\CustomerProduct
 *
 * @property int $id
 * @property string|null $name
 * @property int $published
 * @property int $status
 * @property string|null $added_by
 * @property int|null $user_id
 * @property int|null $category_id
 * @property int|null $subcategory_id
 * @property int|null $subsubcategory_id
 * @property int|null $brand_id
 * @property string|null $photos
 * @property string|null $thumbnail_img
 * @property string|null $conditon
 * @property string|null $location
 * @property string|null $video_provider
 * @property string|null $video_link
 * @property string|null $unit
 * @property string|null $tags
 * @property string|null $description
 * @property float|null $unit_price
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property string|null $meta_img
 * @property string|null $pdf
 * @property string|null $slug
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\Brand|null $brand
 * @property-read \App\Models\Category|null $category
 * @property-read \App\Models\City|null $city
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CustomerProductTranslation> $customer_product_translations
 * @property-read int|null $customer_product_translations_count
 * @property-read \App\Models\State|null $state
 * @property-read \App\Models\SubCategory|null $subcategory
 * @property-read \App\Models\SubSubCategory|null $subsubcategory
 * @property-read \App\Models\Upload|null $thumbnail
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerProduct isActiveAndApproval()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerProduct query()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerProduct whereAddedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerProduct whereBrandId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerProduct whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerProduct whereConditon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerProduct whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerProduct whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerProduct whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerProduct whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerProduct whereMetaDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerProduct whereMetaImg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerProduct whereMetaTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerProduct whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerProduct wherePdf($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerProduct wherePhotos($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerProduct wherePublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerProduct whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerProduct whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerProduct whereSubcategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerProduct whereSubsubcategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerProduct whereTags($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerProduct whereThumbnailImg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerProduct whereUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerProduct whereUnitPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerProduct whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerProduct whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerProduct whereVideoLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerProduct whereVideoProvider($value)
 */
	class CustomerProduct extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\CustomerProductTranslation
 *
 * @property int $id
 * @property int $customer_product_id
 * @property string|null $name
 * @property string|null $unit
 * @property string|null $description
 * @property string $lang
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\CustomerProduct|null $customer_product
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerProductTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerProductTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerProductTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerProductTranslation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerProductTranslation whereCustomerProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerProductTranslation whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerProductTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerProductTranslation whereLang($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerProductTranslation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerProductTranslation whereUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerProductTranslation whereUpdatedAt($value)
 */
	class CustomerProductTranslation extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\DeliveryBoy
 *
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|DeliveryBoy newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DeliveryBoy newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DeliveryBoy query()
 */
	class DeliveryBoy extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\DeliveryBoyCollection
 *
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|DeliveryBoyCollection newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DeliveryBoyCollection newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DeliveryBoyCollection query()
 */
	class DeliveryBoyCollection extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\DeliveryBoyPayment
 *
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|DeliveryBoyPayment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DeliveryBoyPayment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DeliveryBoyPayment query()
 */
	class DeliveryBoyPayment extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\DeliveryHistory
 *
 * @property-read \App\Models\Order|null $order
 * @method static \Illuminate\Database\Eloquent\Builder|DeliveryHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DeliveryHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DeliveryHistory query()
 */
	class DeliveryHistory extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\EmailAllowedClient
 *
 * @property int $id
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|EmailAllowedClient newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmailAllowedClient newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmailAllowedClient query()
 * @method static \Illuminate\Database\Eloquent\Builder|EmailAllowedClient whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailAllowedClient whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailAllowedClient whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailAllowedClient whereUpdatedAt($value)
 */
	class EmailAllowedClient extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Emirate
 *
 * @property int $id
 * @property array $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $translations
 * @method static \Illuminate\Database\Eloquent\Builder|Emirate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Emirate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Emirate query()
 * @method static \Illuminate\Database\Eloquent\Builder|Emirate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Emirate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Emirate whereJsonContainsLocale(string $column, string $locale, ?mixed $value)
 * @method static \Illuminate\Database\Eloquent\Builder|Emirate whereJsonContainsLocales(string $column, array $locales, ?mixed $value)
 * @method static \Illuminate\Database\Eloquent\Builder|Emirate whereLocale(string $column, string $locale)
 * @method static \Illuminate\Database\Eloquent\Builder|Emirate whereLocales(string $column, array $locales)
 * @method static \Illuminate\Database\Eloquent\Builder|Emirate whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Emirate whereUpdatedAt($value)
 */
	class Emirate extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\FirebaseNotification
 *
 * @property int $id
 * @property string|null $title
 * @property string|null $text
 * @property string $item_type
 * @property int $item_type_id
 * @property int $receiver_id
 * @property int $is_read
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|FirebaseNotification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FirebaseNotification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FirebaseNotification query()
 * @method static \Illuminate\Database\Eloquent\Builder|FirebaseNotification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FirebaseNotification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FirebaseNotification whereIsRead($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FirebaseNotification whereItemType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FirebaseNotification whereItemTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FirebaseNotification whereReceiverId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FirebaseNotification whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FirebaseNotification whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FirebaseNotification whereUpdatedAt($value)
 */
	class FirebaseNotification extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\FlashDeal
 *
 * @property int $id
 * @property string|null $title
 * @property int|null $start_date
 * @property int|null $end_date
 * @property int $status
 * @property int $featured
 * @property string|null $background_color
 * @property string|null $text_color
 * @property string|null $banner
 * @property string|null $slug
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FlashDealProduct> $flash_deal_products
 * @property-read int|null $flash_deal_products_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FlashDealTranslation> $flash_deal_translations
 * @property-read int|null $flash_deal_translations_count
 * @method static \Illuminate\Database\Eloquent\Builder|FlashDeal isActiveAndFeatured()
 * @method static \Illuminate\Database\Eloquent\Builder|FlashDeal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FlashDeal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FlashDeal query()
 * @method static \Illuminate\Database\Eloquent\Builder|FlashDeal whereBackgroundColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlashDeal whereBanner($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlashDeal whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlashDeal whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlashDeal whereFeatured($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlashDeal whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlashDeal whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlashDeal whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlashDeal whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlashDeal whereTextColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlashDeal whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlashDeal whereUpdatedAt($value)
 */
	class FlashDeal extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\FlashDealProduct
 *
 * @property int $id
 * @property int $flash_deal_id
 * @property int $product_id
 * @property float|null $discount
 * @property string|null $discount_type
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\Product|null $product
 * @method static \Illuminate\Database\Eloquent\Builder|FlashDealProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FlashDealProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FlashDealProduct query()
 * @method static \Illuminate\Database\Eloquent\Builder|FlashDealProduct whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlashDealProduct whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlashDealProduct whereDiscountType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlashDealProduct whereFlashDealId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlashDealProduct whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlashDealProduct whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlashDealProduct whereUpdatedAt($value)
 */
	class FlashDealProduct extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\FlashDealTranslation
 *
 * @property int $id
 * @property int $flash_deal_id
 * @property string $title
 * @property string $lang
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\FlashDeal|null $flash_deal
 * @method static \Illuminate\Database\Eloquent\Builder|FlashDealTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FlashDealTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FlashDealTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|FlashDealTranslation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlashDealTranslation whereFlashDealId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlashDealTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlashDealTranslation whereLang($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlashDealTranslation whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlashDealTranslation whereUpdatedAt($value)
 */
	class FlashDealTranslation extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\FollowSeller
 *
 * @property int $user_id
 * @property int $shop_id
 * @property-read \App\Models\Shop|null $shop
 * @method static \Illuminate\Database\Eloquent\Builder|FollowSeller newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FollowSeller newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FollowSeller query()
 * @method static \Illuminate\Database\Eloquent\Builder|FollowSeller whereShopId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FollowSeller whereUserId($value)
 */
	class FollowSeller extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Language
 *
 * @property int $id
 * @property string $name
 * @property string $code
 * @property string|null $app_lang_code
 * @property int $rtl
 * @property int $status
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Language newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Language newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Language query()
 * @method static \Illuminate\Database\Eloquent\Builder|Language whereAppLangCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Language whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Language whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Language whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Language whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Language whereRtl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Language whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Language whereUpdatedAt($value)
 */
	class Language extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ManualPaymentMethod
 *
 * @property int $id
 * @property string|null $type
 * @property string|null $heading
 * @property string|null $description
 * @property string|null $bank_info
 * @property string|null $photo
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ManualPaymentMethod newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ManualPaymentMethod newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ManualPaymentMethod query()
 * @method static \Illuminate\Database\Eloquent\Builder|ManualPaymentMethod whereBankInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ManualPaymentMethod whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ManualPaymentMethod whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ManualPaymentMethod whereHeading($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ManualPaymentMethod whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ManualPaymentMethod wherePhoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ManualPaymentMethod whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ManualPaymentMethod whereUpdatedAt($value)
 */
	class ManualPaymentMethod extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\MeasurementPoint
 *
 * @method static \Illuminate\Database\Eloquent\Builder|MeasurementPoint newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MeasurementPoint newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MeasurementPoint query()
 */
	class MeasurementPoint extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Message
 *
 * @property int $id
 * @property int $conversation_id
 * @property int $user_id
 * @property string|null $message
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\Conversation|null $conversation
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Message newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Message newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Message query()
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereConversationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereUserId($value)
 */
	class Message extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Order
 *
 * @property int $id
 * @property int|null $combined_order_id
 * @property int|null $user_id
 * @property int|null $guest_id
 * @property int|null $seller_id
 * @property string|null $shipping_address
 * @property string|null $additional_info
 * @property string $shipping_type
 * @property string $order_from
 * @property int $pickup_point_id
 * @property int|null $carrier_id
 * @property string|null $delivery_status
 * @property string|null $payment_type
 * @property int $manual_payment
 * @property string|null $manual_payment_data
 * @property string|null $payment_status
 * @property string|null $payment_details
 * @property float|null $grand_total
 * @property float $coupon_discount
 * @property string|null $code
 * @property string|null $tracking_code
 * @property int $date
 * @property int $viewed
 * @property int $delivery_viewed
 * @property int|null $payment_status_viewed
 * @property int $commission_calculated
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AffiliateLog> $affiliate_log
 * @property-read int|null $affiliate_log_count
 * @property-read \App\Models\Carrier|null $carrier
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ClubPoint> $club_point
 * @property-read int|null $club_point_count
 * @property-read \App\Models\User|null $delivery_boy
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OrderDetail> $orderDetails
 * @property-read int|null $order_details_count
 * @property-read \App\Models\PickupPoint|null $pickup_point
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ProxyPayment> $proxy_cart_reference_id
 * @property-read int|null $proxy_cart_reference_id_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RefundRequest> $refund_requests
 * @property-read int|null $refund_requests_count
 * @property-read \App\Models\Shop|null $shop
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order query()
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereAdditionalInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCarrierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCombinedOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCommissionCalculated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCouponDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDeliveryStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDeliveryViewed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereGrandTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereGuestId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereManualPayment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereManualPaymentData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereOrderFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePaymentDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePaymentStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePaymentStatusViewed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePaymentType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePickupPointId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereSellerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereShippingAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereShippingType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereTrackingCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereViewed($value)
 */
	class Order extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\OrderDetail
 *
 * @property int $id
 * @property int $order_id
 * @property int|null $seller_id
 * @property int $product_id
 * @property string|null $variation
 * @property float|null $price
 * @property float $tax
 * @property float $shipping_cost
 * @property int|null $quantity
 * @property string $payment_status
 * @property string|null $delivery_status
 * @property string|null $shipping_type
 * @property int|null $pickup_point_id
 * @property string|null $product_referral_code
 * @property float $earn_point
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AffiliateLog> $affiliate_log
 * @property-read int|null $affiliate_log_count
 * @property-read \App\Models\Order|null $order
 * @property-read \App\Models\PickupPoint|null $pickup_point
 * @property-read \App\Models\Product|null $product
 * @property-read \App\Models\RefundRequest|null $refund_request
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetail whereDeliveryStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetail whereEarnPoint($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetail whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetail wherePaymentStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetail wherePickupPointId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetail wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetail whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetail whereProductReferralCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetail whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetail whereSellerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetail whereShippingCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetail whereShippingType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetail whereTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetail whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetail whereVariation($value)
 */
	class OrderDetail extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\OtpConfiguration
 *
 * @property int $id
 * @property string|null $type
 * @property string|null $value
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|OtpConfiguration newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OtpConfiguration newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OtpConfiguration query()
 * @method static \Illuminate\Database\Eloquent\Builder|OtpConfiguration whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OtpConfiguration whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OtpConfiguration whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OtpConfiguration whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OtpConfiguration whereValue($value)
 */
	class OtpConfiguration extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Page
 *
 * @property int $id
 * @property string $type
 * @property string|null $title
 * @property string|null $slug
 * @property string|null $content
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property string|null $keywords
 * @property string|null $meta_image
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PageTranslation> $page_translations
 * @property-read int|null $page_translations_count
 * @method static \Illuminate\Database\Eloquent\Builder|Page newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Page newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Page query()
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereKeywords($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereMetaDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereMetaImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereMetaTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereUpdatedAt($value)
 */
	class Page extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\PageTranslation
 *
 * @property int $id
 * @property int $page_id
 * @property string $title
 * @property string $content
 * @property string $lang
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\Page|null $page
 * @method static \Illuminate\Database\Eloquent\Builder|PageTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PageTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PageTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|PageTranslation whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PageTranslation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PageTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PageTranslation whereLang($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PageTranslation wherePageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PageTranslation whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PageTranslation whereUpdatedAt($value)
 */
	class PageTranslation extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\PasswordReset
 *
 * @property string $email
 * @property string $token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @method static \Illuminate\Database\Eloquent\Builder|PasswordReset newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PasswordReset newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PasswordReset query()
 * @method static \Illuminate\Database\Eloquent\Builder|PasswordReset whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PasswordReset whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PasswordReset whereToken($value)
 */
	class PasswordReset extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Payment
 *
 * @property int $id
 * @property int $seller_id
 * @property float $amount
 * @property string|null $payment_details
 * @property string|null $payment_method
 * @property string|null $txn_code
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Payment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Payment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Payment query()
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment wherePaymentDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereSellerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereTxnCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereUpdatedAt($value)
 */
	class Payment extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\PayoutInformation
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $bank_name
 * @property string|null $account_name
 * @property string|null $account_number
 * @property string|null $iban
 * @property string|null $swift_code
 * @property string|null $iban_certificate
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $saveasdraft
 * @method static \Illuminate\Database\Eloquent\Builder|PayoutInformation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PayoutInformation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PayoutInformation query()
 * @method static \Illuminate\Database\Eloquent\Builder|PayoutInformation whereAccountName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PayoutInformation whereAccountNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PayoutInformation whereBankName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PayoutInformation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PayoutInformation whereIban($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PayoutInformation whereIbanCertificate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PayoutInformation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PayoutInformation whereSaveasdraft($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PayoutInformation whereSwiftCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PayoutInformation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PayoutInformation whereUserId($value)
 */
	class PayoutInformation extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Permission
 *
 * @property int $id
 * @property string $name
 * @property string|null $section
 * @property string $guard_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|Permission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission query()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission role($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereGuardName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereSection($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereUpdatedAt($value)
 */
	class Permission extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\PickupPoint
 *
 * @property int $id
 * @property int $staff_id
 * @property string $name
 * @property string $address
 * @property string $phone
 * @property int|null $pick_up_status
 * @property int|null $cash_on_pickup_status
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PickupPointTranslation> $pickup_point_translations
 * @property-read int|null $pickup_point_translations_count
 * @property-read \App\Models\Staff|null $staff
 * @method static \Illuminate\Database\Eloquent\Builder|PickupPoint isActive()
 * @method static \Illuminate\Database\Eloquent\Builder|PickupPoint newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PickupPoint newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PickupPoint query()
 * @method static \Illuminate\Database\Eloquent\Builder|PickupPoint whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PickupPoint whereCashOnPickupStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PickupPoint whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PickupPoint whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PickupPoint whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PickupPoint wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PickupPoint wherePickUpStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PickupPoint whereStaffId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PickupPoint whereUpdatedAt($value)
 */
	class PickupPoint extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\PickupPointTranslation
 *
 * @property int $id
 * @property int $pickup_point_id
 * @property string $name
 * @property string $address
 * @property string $lang
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\PickupPoint|null $poickup_point
 * @method static \Illuminate\Database\Eloquent\Builder|PickupPointTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PickupPointTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PickupPointTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|PickupPointTranslation whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PickupPointTranslation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PickupPointTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PickupPointTranslation whereLang($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PickupPointTranslation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PickupPointTranslation wherePickupPointId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PickupPointTranslation whereUpdatedAt($value)
 */
	class PickupPointTranslation extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Policy
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Policy newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Policy newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Policy query()
 */
	class Policy extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\PricingConfiguration
 *
 * @property int $id
 * @property int $id_products
 * @property int $from
 * @property int $to
 * @property float $unit_price
 * @property string|null $discount_start_datetime
 * @property string|null $discount_end_datetime
 * @property string|null $discount_type
 * @property float|null $discount_amount
 * @property float|null $discount_percentage
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|PricingConfiguration newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PricingConfiguration newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PricingConfiguration query()
 * @method static \Illuminate\Database\Eloquent\Builder|PricingConfiguration whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PricingConfiguration whereDiscountAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PricingConfiguration whereDiscountEndDatetime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PricingConfiguration whereDiscountPercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PricingConfiguration whereDiscountStartDatetime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PricingConfiguration whereDiscountType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PricingConfiguration whereFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PricingConfiguration whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PricingConfiguration whereIdProducts($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PricingConfiguration whereTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PricingConfiguration whereUnitPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PricingConfiguration whereUpdatedAt($value)
 */
	class PricingConfiguration extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Product
 *
 * @property int $id
 * @property string $name
 * @property string $added_by
 * @property int $user_id
 * @property int $category_id
 * @property int|null $brand_id
 * @property string|null $photos
 * @property string|null $thumbnail_img
 * @property string|null $video_provider
 * @property string|null $video_link
 * @property string|null $tags
 * @property string|null $description
 * @property float $unit_price
 * @property float|null $purchase_price
 * @property int $variant_product
 * @property string $attributes
 * @property string|null $choice_options
 * @property string|null $colors
 * @property string|null $variations
 * @property int $todays_deal
 * @property int $published
 * @property int $approved
 * @property string $stock_visibility_state
 * @property int $cash_on_delivery 1 = On, 0 = Off
 * @property int $featured
 * @property int $seller_featured
 * @property int $current_stock
 * @property string|null $unit
 * @property string|null $weight
 * @property int $min_qty
 * @property int|null $low_stock_quantity
 * @property float|null $discount
 * @property string|null $discount_type
 * @property int|null $discount_start_date
 * @property int|null $discount_end_date
 * @property float|null $tax
 * @property string|null $tax_type
 * @property string|null $shipping_type
 * @property float $shipping_cost
 * @property int $is_quantity_multiplied 1 = Mutiplied with shipping cost
 * @property int|null $est_shipping_days
 * @property int $num_of_sale
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property string|null $meta_img
 * @property string|null $pdf
 * @property string $slug
 * @property int $refundable
 * @property float $earn_point
 * @property float $rating
 * @property string|null $barcode
 * @property int $digital
 * @property int $auction_product
 * @property string|null $file_name
 * @property string|null $file_path
 * @property string|null $external_link
 * @property string|null $external_link_btn
 * @property int $wholesale_product
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property string $country_code
 * @property string $manufacturer
 * @property int $parent_id
 * @property string|null $sku
 * @property int $shipping
 * @property int $is_parent
 * @property int $vat
 * @property int $vat_sample
 * @property string|null $sample_description
 * @property string|null $short_description
 * @property int|null $sample_price
 * @property int $is_draft
 * @property string|null $rejection_reason
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int $activate_third_party
 * @property int|null $length
 * @property int|null $width
 * @property int|null $height
 * @property int|null $min_third_party
 * @property int|null $max_third_party
 * @property string|null $breakable
 * @property string|null $unit_third_party
 * @property string|null $shipper_sample
 * @property int|null $estimated_sample
 * @property int|null $estimated_shipping_sample
 * @property string|null $paid_sample
 * @property float|null $shipping_amount
 * @property int $sample_available
 * @property string|null $unit_weight
 * @property string|null $stock_after_create
 * @property int $catalog
 * @property int $added_from_catalog
 * @property int $last_version
 * @property int $product_added_from_catalog
 * @property int $activate_third_party_sample
 * @property int|null $length_sample
 * @property int|null $width_sample
 * @property int|null $height_sample
 * @property string|null $package_weight_sample
 * @property string|null $weight_unit_sample
 * @property string|null $breakable_sample
 * @property string|null $unit_third_party_sample
 * @property int|null $min_third_party_sample
 * @property int|null $max_third_party_sample
 * @property int|null $product_catalog_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AuctionProductBid> $bids
 * @property-read int|null $bids_count
 * @property-read \App\Models\Brand|null $brand
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Cart> $carts
 * @property-read int|null $carts_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Category> $categories
 * @property-read int|null $categories_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Product> $children
 * @property-read int|null $children_count
 * @property-read \App\Models\FlashDealProduct|null $flash_deal_product
 * @property-read \App\Models\Category|null $main_category
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\UploadProducts> $medias
 * @property-read int|null $medias_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OrderDetail> $orderDetails
 * @property-read int|null $order_details_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ProductAttributeValues> $productAttributeValues
 * @property-read int|null $product_attribute_values_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ProductQuery> $product_queries
 * @property-read int|null $product_queries_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ProductTranslation> $product_translations
 * @property-read int|null $product_translations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Review> $reviews
 * @property-read int|null $reviews_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Venturecraft\Revisionable\Revision> $revisionHistory
 * @property-read int|null $revision_history_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ProductStock> $stocks
 * @property-read int|null $stocks_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ProductTax> $taxes
 * @property-read int|null $taxes_count
 * @property-read \App\Models\Upload|null $thumbnail
 * @property-read \App\Models\User|null $user
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Product> $variants
 * @property-read int|null $variants_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Wishlist> $wishlists
 * @property-read int|null $wishlists_count
 * @method static \Illuminate\Database\Eloquent\Builder|Product digital()
 * @method static \Illuminate\Database\Eloquent\Builder|Product isApprovedPublished()
 * @method static \Illuminate\Database\Eloquent\Builder|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Product physical()
 * @method static \Illuminate\Database\Eloquent\Builder|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereActivateThirdParty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereActivateThirdPartySample($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereAddedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereAddedFromCatalog($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereApproved($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereAttributes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereAuctionProduct($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereBarcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereBrandId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereBreakable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereBreakableSample($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCashOnDelivery($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCatalog($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereChoiceOptions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereColors($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCountryCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCurrentStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereDigital($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereDiscountEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereDiscountStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereDiscountType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereEarnPoint($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereEstShippingDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereEstimatedSample($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereEstimatedShippingSample($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereExternalLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereExternalLinkBtn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereFeatured($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereHeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereHeightSample($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereIsDraft($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereIsParent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereIsQuantityMultiplied($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereLastVersion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereLength($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereLengthSample($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereLowStockQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereManufacturer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereMaxThirdParty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereMaxThirdPartySample($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereMetaDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereMetaImg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereMetaTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereMinQty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereMinThirdParty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereMinThirdPartySample($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereNumOfSale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product wherePackageWeightSample($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product wherePaidSample($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product wherePdf($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product wherePhotos($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereProductAddedFromCatalog($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereProductCatalogId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product wherePublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product wherePurchasePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereRefundable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereRejectionReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereSampleAvailable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereSampleDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereSamplePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereSellerFeatured($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereShipperSample($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereShipping($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereShippingAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereShippingCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereShippingType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereShortDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereSku($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereStockAfterCreate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereStockVisibilityState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereTags($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereTaxType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereThumbnailImg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereTodaysDeal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereUnitPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereUnitThirdParty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereUnitThirdPartySample($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereUnitWeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereVariantProduct($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereVariations($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereVat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereVatSample($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereVideoLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereVideoProvider($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereWeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereWeightUnitSample($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereWholesaleProduct($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereWidth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereWidthSample($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Product withoutTrashed()
 */
	class Product extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ProductAttributeValueCatalog
 *
 * @property int $id
 * @property int $catalog_id
 * @property int $id_attribute
 * @property int|null $id_units
 * @property int|null $id_values
 * @property int|null $id_colors
 * @property string $value
 * @property int $is_variant
 * @property int $is_general
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttributeValueCatalog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttributeValueCatalog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttributeValueCatalog query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttributeValueCatalog whereCatalogId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttributeValueCatalog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttributeValueCatalog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttributeValueCatalog whereIdAttribute($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttributeValueCatalog whereIdColors($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttributeValueCatalog whereIdUnits($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttributeValueCatalog whereIdValues($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttributeValueCatalog whereIsGeneral($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttributeValueCatalog whereIsVariant($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttributeValueCatalog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttributeValueCatalog whereValue($value)
 */
	class ProductAttributeValueCatalog extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ProductAttributeValues
 *
 * @property int $id
 * @property int $id_products
 * @property int $id_attribute
 * @property int|null $id_units
 * @property int|null $id_values
 * @property int|null $id_colors
 * @property string $value
 * @property int $is_variant
 * @property int $is_general
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Attribute|null $attribute
 * @property-read \App\Models\AttributeValue|null $attributeValues
 * @property-read \App\Models\Color|null $color
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Venturecraft\Revisionable\Revision> $revisionHistory
 * @property-read int|null $revision_history_count
 * @property-read \App\Models\Unity|null $unity
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttributeValues newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttributeValues newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttributeValues query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttributeValues whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttributeValues whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttributeValues whereIdAttribute($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttributeValues whereIdColors($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttributeValues whereIdProducts($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttributeValues whereIdUnits($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttributeValues whereIdValues($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttributeValues whereIsGeneral($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttributeValues whereIsVariant($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttributeValues whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttributeValues whereValue($value)
 */
	class ProductAttributeValues extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ProductCatalog
 *
 * @property int $id
 * @property string $name
 * @property string $added_by
 * @property int $user_id
 * @property int $category_id
 * @property int $product_id
 * @property int|null $brand_id
 * @property string|null $photos
 * @property string|null $thumbnail_img
 * @property string|null $video_provider
 * @property string|null $video_link
 * @property string|null $tags
 * @property string|null $description
 * @property float $unit_price
 * @property float|null $purchase_price
 * @property int $variant_product
 * @property string $attributes
 * @property string|null $choice_options
 * @property string|null $colors
 * @property string|null $variations
 * @property int $todays_deal
 * @property int $published
 * @property int $approved
 * @property string $stock_visibility_state
 * @property int $cash_on_delivery
 * @property int $featured
 * @property int $seller_featured
 * @property int $current_stock
 * @property string|null $unit
 * @property string|null $weight
 * @property int $min_qty
 * @property int|null $low_stock_quantity
 * @property float|null $discount
 * @property string|null $discount_type
 * @property int|null $discount_start_date
 * @property int|null $discount_end_date
 * @property float|null $tax
 * @property string|null $tax_type
 * @property string $shipping_type
 * @property float $shipping_cost
 * @property int $is_quantity_multiplied
 * @property int|null $est_shipping_days
 * @property int $num_of_sale
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property string|null $meta_img
 * @property string|null $pdf
 * @property string $slug
 * @property int $refundable
 * @property float $earn_point
 * @property float $rating
 * @property string|null $barcode
 * @property int $digital
 * @property int $auction_product
 * @property string|null $file_name
 * @property string|null $file_path
 * @property string|null $external_link
 * @property string $external_link_btn
 * @property int $wholesale_product
 * @property string $country_code
 * @property string $manufacturer
 * @property int $parent_id
 * @property string|null $sku
 * @property int $shipping
 * @property int $is_parent
 * @property int $vat
 * @property int $vat_sample
 * @property string|null $sample_description
 * @property string|null $short_description
 * @property int|null $sample_price
 * @property int $is_draft
 * @property string|null $rejection_reason
 * @property int $activate_third_party
 * @property int|null $length
 * @property int|null $width
 * @property int|null $height
 * @property int|null $min_third_party
 * @property int|null $max_third_party
 * @property string|null $breakable
 * @property string|null $unit_third_party
 * @property string|null $shipper_sample
 * @property int|null $estimated_sample
 * @property int|null $estimated_shipping_sample
 * @property string|null $paid_sample
 * @property float|null $shipping_amount
 * @property int $sample_available
 * @property string|null $unit_weight
 * @property string|null $deleted_at
 * @property string|null $stock_after_create
 * @property int $catalog
 * @property int $added_from_catalog
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $last_version
 * @property int $product_added_from_catalog
 * @property int $activate_third_party_sample
 * @property int|null $length_sample
 * @property int|null $width_sample
 * @property int|null $height_sample
 * @property string|null $package_weight_sample
 * @property string|null $weight_unit_sample
 * @property string|null $breakable_sample
 * @property string|null $unit_third_party_sample
 * @property int|null $min_third_party_sample
 * @property int|null $max_third_party_sample
 * @property int|null $product_catalog_id
 * @property-read \App\Models\Brand|null $brand
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereActivateThirdParty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereActivateThirdPartySample($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereAddedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereAddedFromCatalog($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereApproved($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereAttributes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereAuctionProduct($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereBarcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereBrandId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereBreakable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereBreakableSample($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereCashOnDelivery($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereCatalog($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereChoiceOptions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereColors($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereCountryCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereCurrentStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereDigital($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereDiscountEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereDiscountStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereDiscountType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereEarnPoint($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereEstShippingDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereEstimatedSample($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereEstimatedShippingSample($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereExternalLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereExternalLinkBtn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereFeatured($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereHeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereHeightSample($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereIsDraft($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereIsParent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereIsQuantityMultiplied($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereLastVersion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereLength($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereLengthSample($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereLowStockQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereManufacturer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereMaxThirdParty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereMaxThirdPartySample($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereMetaDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereMetaImg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereMetaTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereMinQty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereMinThirdParty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereMinThirdPartySample($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereNumOfSale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog wherePackageWeightSample($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog wherePaidSample($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog wherePdf($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog wherePhotos($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereProductAddedFromCatalog($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereProductCatalogId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog wherePublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog wherePurchasePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereRefundable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereRejectionReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereSampleAvailable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereSampleDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereSamplePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereSellerFeatured($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereShipperSample($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereShipping($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereShippingAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereShippingCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereShippingType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereShortDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereSku($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereStockAfterCreate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereStockVisibilityState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereTags($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereTaxType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereThumbnailImg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereTodaysDeal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereUnitPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereUnitThirdParty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereUnitThirdPartySample($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereUnitWeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereVariantProduct($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereVariations($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereVat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereVatSample($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereVideoLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereVideoProvider($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereWeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereWeightUnitSample($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereWholesaleProduct($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereWidth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCatalog whereWidthSample($value)
 */
	class ProductCatalog extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ProductCategory
 *
 * @property int $product_id
 * @property int $category_id
 * @property-read \App\Models\Category|null $category
 * @property-read \App\Models\Product|null $product
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCategory whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCategory whereProductId($value)
 */
	class ProductCategory extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ProductQuery
 *
 * @property int $id
 * @property int $customer_id
 * @property int $seller_id
 * @property int $product_id
 * @property string $question
 * @property string|null $reply
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Product|null $product
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|ProductQuery newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductQuery newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductQuery query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductQuery whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductQuery whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductQuery whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductQuery whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductQuery whereQuestion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductQuery whereReply($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductQuery whereSellerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductQuery whereUpdatedAt($value)
 */
	class ProductQuery extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ProductStock
 *
 * @property int $id
 * @property int $product_id
 * @property string $variant
 * @property string|null $sku
 * @property float $price
 * @property int $qty
 * @property int|null $image
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\Product|null $product
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\WholesalePrice> $wholesalePrices
 * @property-read int|null $wholesale_prices_count
 * @method static \Illuminate\Database\Eloquent\Builder|ProductStock newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductStock newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductStock query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductStock whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductStock whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductStock whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductStock wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductStock whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductStock whereQty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductStock whereSku($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductStock whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductStock whereVariant($value)
 */
	class ProductStock extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ProductTax
 *
 * @property int $id
 * @property int $product_id
 * @property int $tax_id
 * @property float $tax
 * @property string $tax_type
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTax newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTax newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTax query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTax whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTax whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTax whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTax whereTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTax whereTaxId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTax whereTaxType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTax whereUpdatedAt($value)
 */
	class ProductTax extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ProductTranslation
 *
 * @property int $id
 * @property int $product_id
 * @property string|null $name
 * @property string|null $unit
 * @property string|null $description
 * @property string $lang
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\Product|null $product
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTranslation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTranslation whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTranslation whereLang($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTranslation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTranslation whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTranslation whereUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTranslation whereUpdatedAt($value)
 */
	class ProductTranslation extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ProposedPayoutChange
 *
 * @property int $id
 * @property int $user_id
 * @property mixed $modified_fields
 * @property string $status
 * @property int $admin_viewed
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $vendorAdmin
 * @method static \Illuminate\Database\Eloquent\Builder|ProposedPayoutChange newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProposedPayoutChange newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProposedPayoutChange query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProposedPayoutChange whereAdminViewed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProposedPayoutChange whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProposedPayoutChange whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProposedPayoutChange whereModifiedFields($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProposedPayoutChange whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProposedPayoutChange whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProposedPayoutChange whereUserId($value)
 */
	class ProposedPayoutChange extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ProxyPayment
 *
 * @property int $id
 * @property string $payment_type
 * @property string $reference_id
 * @property int|null $order_id
 * @property int|null $package_id
 * @property int $user_id
 * @property float $amount
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ProxyPayment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProxyPayment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProxyPayment query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProxyPayment whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProxyPayment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProxyPayment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProxyPayment whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProxyPayment wherePackageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProxyPayment wherePaymentType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProxyPayment whereReferenceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProxyPayment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProxyPayment whereUserId($value)
 */
	class ProxyPayment extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\RefundRequest
 *
 * @property int $id
 * @property int $user_id
 * @property int $order_id
 * @property int $order_detail_id
 * @property int $seller_id
 * @property int $seller_approval
 * @property int $admin_approval
 * @property float $refund_amount
 * @property string|null $reason
 * @property int $admin_seen
 * @property int $refund_status
 * @property string|null $reject_reason
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\Order|null $order
 * @property-read \App\Models\OrderDetail|null $orderDetail
 * @property-read \App\Models\User|null $seller
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|RefundRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RefundRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RefundRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder|RefundRequest whereAdminApproval($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RefundRequest whereAdminSeen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RefundRequest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RefundRequest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RefundRequest whereOrderDetailId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RefundRequest whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RefundRequest whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RefundRequest whereRefundAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RefundRequest whereRefundStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RefundRequest whereRejectReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RefundRequest whereSellerApproval($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RefundRequest whereSellerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RefundRequest whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RefundRequest whereUserId($value)
 */
	class RefundRequest extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Review
 *
 * @property int $id
 * @property int $product_id
 * @property int $user_id
 * @property int $rating
 * @property string $comment
 * @property string|null $photos
 * @property int $status
 * @property int $viewed
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\Product|null $product
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Review newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Review newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Review query()
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Review wherePhotos($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereViewed($value)
 */
	class Review extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Revision
 *
 * @property int $id
 * @property string $revisionable_type
 * @property int $revisionable_id
 * @property int|null $user_id
 * @property string $key
 * @property string|null $old_value
 * @property string|null $new_value
 * @property int|null $action_number
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|Revision newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Revision newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Revision onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Revision query()
 * @method static \Illuminate\Database\Eloquent\Builder|Revision whereActionNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Revision whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Revision whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Revision whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Revision whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Revision whereNewValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Revision whereOldValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Revision whereRevisionableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Revision whereRevisionableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Revision whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Revision whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Revision withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Revision withoutTrashed()
 */
	class Revision extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Role
 *
 * @property int $id
 * @property string $name
 * @property string $guard_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $created_by
 * @property string|null $description
 * @property int|null $role_type
 * @property int|null $package_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RoleTranslation> $role_translations
 * @property-read int|null $role_translations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|Role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Role permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|Role query()
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereGuardName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role wherePackageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereRoleType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereUpdatedAt($value)
 */
	class Role extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\RoleHasPermissions
 *
 * @property int $permission_id
 * @property int $role_id
 * @method static \Illuminate\Database\Eloquent\Builder|RoleHasPermissions newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RoleHasPermissions newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RoleHasPermissions query()
 * @method static \Illuminate\Database\Eloquent\Builder|RoleHasPermissions wherePermissionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RoleHasPermissions whereRoleId($value)
 */
	class RoleHasPermissions extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\RoleTranslation
 *
 * @property int $id
 * @property int $role_id
 * @property string $name
 * @property string $lang
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\Role|null $role
 * @method static \Illuminate\Database\Eloquent\Builder|RoleTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RoleTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RoleTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|RoleTranslation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RoleTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RoleTranslation whereLang($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RoleTranslation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RoleTranslation whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RoleTranslation whereUpdatedAt($value)
 */
	class RoleTranslation extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Search
 *
 * @property int $id
 * @property string $query
 * @property int $count
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Search newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Search newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Search query()
 * @method static \Illuminate\Database\Eloquent\Builder|Search whereCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Search whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Search whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Search whereQuery($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Search whereUpdatedAt($value)
 */
	class Search extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Seller
 *
 * @property int $id
 * @property int $user_id
 * @property int|null $seller_package_id
 * @property int $remaining_uploads
 * @property int $remaining_digital_uploads
 * @property string|null $invalid_at
 * @property int|null $remaining_auction_uploads
 * @property float $rating
 * @property int $num_of_reviews
 * @property int $num_of_sale
 * @property int $verification_status
 * @property string|null $verification_info
 * @property int $cash_on_delivery_status
 * @property float $admin_to_pay
 * @property string|null $bank_name
 * @property string|null $bank_acc_name
 * @property string|null $bank_acc_no
 * @property int|null $bank_routing_no
 * @property int $bank_payment_status
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Payment> $payments
 * @property-read int|null $payments_count
 * @property-read \App\Models\SellerPackage|null $seller_package
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Seller newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Seller newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Seller query()
 * @method static \Illuminate\Database\Eloquent\Builder|Seller whereAdminToPay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seller whereBankAccName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seller whereBankAccNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seller whereBankName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seller whereBankPaymentStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seller whereBankRoutingNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seller whereCashOnDeliveryStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seller whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seller whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seller whereInvalidAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seller whereNumOfReviews($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seller whereNumOfSale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seller whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seller whereRemainingAuctionUploads($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seller whereRemainingDigitalUploads($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seller whereRemainingUploads($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seller whereSellerPackageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seller whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seller whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seller whereVerificationInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seller whereVerificationStatus($value)
 */
	class Seller extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\SellerLease
 *
 * @property int $id
 * @property int $vendor_id
 * @property int $package_id
 * @property string|null $total
 * @property string|null $discount
 * @property string|null $start_date
 * @property string|null $end_date
 * @property string|null $roles
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\SellerPackage $package
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|SellerLease newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SellerLease newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SellerLease query()
 * @method static \Illuminate\Database\Eloquent\Builder|SellerLease whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellerLease whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellerLease whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellerLease whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellerLease wherePackageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellerLease whereRoles($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellerLease whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellerLease whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellerLease whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellerLease whereVendorId($value)
 */
	class SellerLease extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\SellerLeaseDetail
 *
 * @property int $id
 * @property int $lease_id
 * @property int|null $role_id
 * @property string|null $amount
 * @property int $is_used
 * @property string $start_date
 * @property string $end_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\SellerLease $lease
 * @property-read \App\Models\Role|null $role
 * @method static \Illuminate\Database\Eloquent\Builder|SellerLeaseDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SellerLeaseDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SellerLeaseDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder|SellerLeaseDetail whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellerLeaseDetail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellerLeaseDetail whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellerLeaseDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellerLeaseDetail whereIsUsed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellerLeaseDetail whereLeaseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellerLeaseDetail whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellerLeaseDetail whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellerLeaseDetail whereUpdatedAt($value)
 */
	class SellerLeaseDetail extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\SellerPackage
 *
 * @property int $id
 * @property string|null $name
 * @property float $amount
 * @property int $product_upload_limit
 * @property string|null $logo
 * @property int $duration
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SellerLease> $leases
 * @property-read int|null $leases_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SellerPackageTranslation> $seller_package_translations
 * @property-read int|null $seller_package_translations_count
 * @property-read \App\Models\Shop|null $shop
 * @method static \Illuminate\Database\Eloquent\Builder|SellerPackage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SellerPackage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SellerPackage query()
 * @method static \Illuminate\Database\Eloquent\Builder|SellerPackage whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellerPackage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellerPackage whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellerPackage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellerPackage whereLogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellerPackage whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellerPackage whereProductUploadLimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellerPackage whereUpdatedAt($value)
 */
	class SellerPackage extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\SellerPackagePayment
 *
 * @property int $id
 * @property int $user_id
 * @property int $seller_package_id
 * @property string|null $payment_method
 * @property string|null $payment_details
 * @property int $approval
 * @property int $offline_payment 1=offline payment
 * 2=online paymnet
 * @property string|null $reciept
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\SellerPackage|null $seller_package
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|SellerPackagePayment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SellerPackagePayment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SellerPackagePayment query()
 * @method static \Illuminate\Database\Eloquent\Builder|SellerPackagePayment whereApproval($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellerPackagePayment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellerPackagePayment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellerPackagePayment whereOfflinePayment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellerPackagePayment wherePaymentDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellerPackagePayment wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellerPackagePayment whereReciept($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellerPackagePayment whereSellerPackageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellerPackagePayment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellerPackagePayment whereUserId($value)
 */
	class SellerPackagePayment extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\SellerPackageTranslation
 *
 * @property int $id
 * @property int $seller_package_id
 * @property string $name
 * @property string $lang
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\SellerPackage|null $seller_package
 * @method static \Illuminate\Database\Eloquent\Builder|SellerPackageTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SellerPackageTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SellerPackageTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|SellerPackageTranslation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellerPackageTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellerPackageTranslation whereLang($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellerPackageTranslation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellerPackageTranslation whereSellerPackageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellerPackageTranslation whereUpdatedAt($value)
 */
	class SellerPackageTranslation extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\SellerWithdrawRequest
 *
 * @property int $id
 * @property int|null $user_id
 * @property float|null $amount
 * @property string|null $message
 * @property int|null $status
 * @property int|null $viewed
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\Shop|null $shop
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|SellerWithdrawRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SellerWithdrawRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SellerWithdrawRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder|SellerWithdrawRequest whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellerWithdrawRequest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellerWithdrawRequest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellerWithdrawRequest whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellerWithdrawRequest whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellerWithdrawRequest whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellerWithdrawRequest whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellerWithdrawRequest whereViewed($value)
 */
	class SellerWithdrawRequest extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Shipper
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Shipper newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Shipper newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Shipper query()
 * @method static \Illuminate\Database\Eloquent\Builder|Shipper whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipper whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipper whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipper whereUpdatedAt($value)
 */
	class Shipper extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ShippersArea
 *
 * @property int $id
 * @property int $shipper_id
 * @property int $emirate_id
 * @property int $area_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ShippersArea newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShippersArea newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShippersArea query()
 * @method static \Illuminate\Database\Eloquent\Builder|ShippersArea whereAreaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShippersArea whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShippersArea whereEmirateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShippersArea whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShippersArea whereShipperId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShippersArea whereUpdatedAt($value)
 */
	class ShippersArea extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Shipping
 *
 * @property int $id
 * @property int $product_id
 * @property int $from_shipping
 * @property int $to_shipping
 * @property string $shipper
 * @property int $estimated_order
 * @property string|null $estimated_shipping
 * @property string|null $paid
 * @property int $vat_shipping
 * @property string|null $shipping_charge
 * @property string|null $flat_rate_shipping
 * @property string|null $charge_per_unit_shipping
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Shipping newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Shipping newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Shipping query()
 * @method static \Illuminate\Database\Eloquent\Builder|Shipping whereChargePerUnitShipping($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipping whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipping whereEstimatedOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipping whereEstimatedShipping($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipping whereFlatRateShipping($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipping whereFromShipping($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipping whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipping wherePaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipping whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipping whereShipper($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipping whereShippingCharge($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipping whereToShipping($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipping whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipping whereVatShipping($value)
 */
	class Shipping extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Shop
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $name
 * @property string|null $logo
 * @property string|null $sliders
 * @property string|null $top_banner
 * @property string|null $banner_full_width_1
 * @property string|null $banners_half_width
 * @property string|null $banner_full_width_2
 * @property string|null $phone
 * @property string|null $address
 * @property float $rating
 * @property int $num_of_reviews
 * @property int $num_of_sale
 * @property int|null $seller_package_id
 * @property int $product_upload_limit
 * @property string|null $package_invalid_at
 * @property int $verification_status
 * @property string|null $verification_info
 * @property int $cash_on_delivery_status
 * @property float $admin_to_pay
 * @property string|null $facebook
 * @property string|null $instagram
 * @property string|null $google
 * @property string|null $twitter
 * @property string|null $youtube
 * @property string|null $slug
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property string|null $pick_up_point_id
 * @property float $shipping_cost
 * @property float|null $delivery_pickup_latitude
 * @property float|null $delivery_pickup_longitude
 * @property string|null $bank_name
 * @property string|null $bank_acc_name
 * @property string|null $bank_acc_no
 * @property int|null $bank_routing_no
 * @property int $bank_payment_status
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FollowSeller> $followers
 * @property-read int|null $followers_count
 * @property-read \App\Models\SellerPackage|null $seller_package
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Shop newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Shop newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Shop query()
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereAdminToPay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereBankAccName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereBankAccNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereBankName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereBankPaymentStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereBankRoutingNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereBannerFullWidth1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereBannerFullWidth2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereBannersHalfWidth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereCashOnDeliveryStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereDeliveryPickupLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereDeliveryPickupLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereFacebook($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereGoogle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereInstagram($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereLogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereMetaDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereMetaTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereNumOfReviews($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereNumOfSale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop wherePackageInvalidAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop wherePickUpPointId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereProductUploadLimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereSellerPackageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereShippingCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereSliders($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereTopBanner($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereTwitter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereVerificationInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereVerificationStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereYoutube($value)
 */
	class Shop extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\SizeChart
 *
 * @property int $id
 * @property string $name
 * @property int $category_id
 * @property string|null $fit_type
 * @property string|null $stretch_type
 * @property string|null $photos
 * @property string|null $description
 * @property string $measurement_points
 * @property string $size_options
 * @property string|null $measurement_option
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\Category|null $category
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SizeChartDetail> $sizeChartDetails
 * @property-read int|null $size_chart_details_count
 * @method static \Illuminate\Database\Eloquent\Builder|SizeChart newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SizeChart newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SizeChart query()
 * @method static \Illuminate\Database\Eloquent\Builder|SizeChart whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SizeChart whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SizeChart whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SizeChart whereFitType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SizeChart whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SizeChart whereMeasurementOption($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SizeChart whereMeasurementPoints($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SizeChart whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SizeChart wherePhotos($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SizeChart whereSizeOptions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SizeChart whereStretchType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SizeChart whereUpdatedAt($value)
 */
	class SizeChart extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\SizeChartDetail
 *
 * @property int $id
 * @property int $size_chart_id
 * @property int $measurement_point_id
 * @property int $attribute_value_id
 * @property string|null $inch_value
 * @property string|null $cen_value
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|SizeChartDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SizeChartDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SizeChartDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder|SizeChartDetail whereAttributeValueId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SizeChartDetail whereCenValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SizeChartDetail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SizeChartDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SizeChartDetail whereInchValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SizeChartDetail whereMeasurementPointId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SizeChartDetail whereSizeChartId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SizeChartDetail whereUpdatedAt($value)
 */
	class SizeChartDetail extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Slider
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Slider newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Slider newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Slider query()
 */
	class Slider extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\SmsTemplate
 *
 * @property int $id
 * @property string $identifier
 * @property string $sms_body
 * @property string|null $template_id
 * @property int $status
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|SmsTemplate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SmsTemplate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SmsTemplate query()
 * @method static \Illuminate\Database\Eloquent\Builder|SmsTemplate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SmsTemplate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SmsTemplate whereIdentifier($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SmsTemplate whereSmsBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SmsTemplate whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SmsTemplate whereTemplateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SmsTemplate whereUpdatedAt($value)
 */
	class SmsTemplate extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Staff
 *
 * @property int $id
 * @property int $user_id
 * @property int $role_id
 * @property int|null $seller_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property int|null $created_by
 * @property-read \App\Models\PickupPoint|null $pick_up_point
 * @property-read \App\Models\Role|null $role
 * @property-read \App\Models\User|null $seller
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Staff newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Staff newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Staff query()
 * @method static \Illuminate\Database\Eloquent\Builder|Staff whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Staff whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Staff whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Staff whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Staff whereSellerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Staff whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Staff whereUserId($value)
 */
	class Staff extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\State
 *
 * @property int $id
 * @property string $name
 * @property int $country_id
 * @property int $status
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property string|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\City> $cities
 * @property-read int|null $cities_count
 * @property-read \App\Models\Country|null $country
 * @method static \Illuminate\Database\Eloquent\Builder|State newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|State newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|State query()
 * @method static \Illuminate\Database\Eloquent\Builder|State whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|State whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|State whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|State whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|State whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|State whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|State whereUpdatedAt($value)
 */
	class State extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\StockDetails
 *
 * @property int $id
 * @property string $operation_type
 * @property int $variant_id
 * @property int $warehouse_id
 * @property int $seller_id
 * @property int|null $order_id
 * @property int $before_quantity
 * @property int $transaction_quantity
 * @property int $after_quantity
 * @property string|null $user_comment
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Product|null $productVariant
 * @property-read \App\Models\User|null $seller
 * @property-read \App\Models\Warehouse|null $warehouse
 * @method static \Illuminate\Database\Eloquent\Builder|StockDetails newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StockDetails newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StockDetails query()
 * @method static \Illuminate\Database\Eloquent\Builder|StockDetails whereAfterQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockDetails whereBeforeQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockDetails whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockDetails whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockDetails whereOperationType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockDetails whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockDetails whereSellerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockDetails whereTransactionQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockDetails whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockDetails whereUserComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockDetails whereVariantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockDetails whereWarehouseId($value)
 */
	class StockDetails extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\StockSummary
 *
 * @property int $id
 * @property int $variant_id
 * @property int $warehouse_id
 * @property int $seller_id
 * @property int $current_total_quantity
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Product|null $productVariant
 * @property-read \App\Models\Warehouse|null $warehouse
 * @method static \Illuminate\Database\Eloquent\Builder|StockSummary newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StockSummary newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StockSummary query()
 * @method static \Illuminate\Database\Eloquent\Builder|StockSummary whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockSummary whereCurrentTotalQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockSummary whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockSummary whereSellerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockSummary whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockSummary whereVariantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockSummary whereWarehouseId($value)
 */
	class StockSummary extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\SubCategory
 *
 * @property int $id
 * @property string $name
 * @property int $category_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SubCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SubCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SubCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SubCategory whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SubCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SubCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SubCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SubCategory whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \App\Models\Category|null $category
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product> $products
 * @property-read int|null $products_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SubSubCategory> $subSubCategories
 * @property-read int|null $sub_sub_categories_count
 */
	class SubCategory extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\SubSubCategory
 *
 * @property int $id
 * @property int $sub_category_id
 * @property string $name
 * @property string $brands
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SubSubCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SubSubCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SubSubCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SubSubCategory whereBrands($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SubSubCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SubSubCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SubSubCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SubSubCategory whereSubCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SubSubCategory whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \App\Models\SubCategory|null $subCategory
 */
	class SubSubCategory extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Subscriber
 *
 * @property int $id
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriber newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriber newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriber query()
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriber whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriber whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriber whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriber whereUpdatedAt($value)
 */
	class Subscriber extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Tax
 *
 * @property int $id
 * @property string $name
 * @property int $tax_status 0 = Inactive, 1 = Active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ProductTax> $product_taxes
 * @property-read int|null $product_taxes_count
 * @method static \Illuminate\Database\Eloquent\Builder|Tax newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tax newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tax query()
 * @method static \Illuminate\Database\Eloquent\Builder|Tax whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tax whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tax whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tax whereTaxStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tax whereUpdatedAt($value)
 */
	class Tax extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Ticket
 *
 * @property int $id
 * @property int $code
 * @property int $user_id
 * @property string $subject
 * @property string|null $details
 * @property string|null $files
 * @property string $status
 * @property int $viewed
 * @property int $client_viewed
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TicketReply> $ticketreplies
 * @property-read int|null $ticketreplies_count
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket query()
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket whereClientViewed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket whereDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket whereFiles($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket whereViewed($value)
 */
	class Ticket extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\TicketReply
 *
 * @property int $id
 * @property int $ticket_id
 * @property int $user_id
 * @property string $reply
 * @property string|null $files
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\Ticket|null $ticket
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|TicketReply newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TicketReply newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TicketReply query()
 * @method static \Illuminate\Database\Eloquent\Builder|TicketReply whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketReply whereFiles($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketReply whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketReply whereReply($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketReply whereTicketId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketReply whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketReply whereUserId($value)
 */
	class TicketReply extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Tour
 *
 * @property int $id
 * @property int $step_number
 * @property string $element_id
 * @property string $title
 * @property string $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TourTranslation> $tour_translation
 * @property-read int|null $tour_translation_count
 * @method static \Illuminate\Database\Eloquent\Builder|Tour newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tour newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tour query()
 * @method static \Illuminate\Database\Eloquent\Builder|Tour whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tour whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tour whereElementId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tour whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tour whereStepNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tour whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tour whereUpdatedAt($value)
 */
	class Tour extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\TourTranslation
 *
 * @property int $id
 * @property int $tour_id
 * @property string $title
 * @property string $description
 * @property string $lang
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Tour|null $tour
 * @method static \Illuminate\Database\Eloquent\Builder|TourTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TourTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TourTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|TourTranslation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TourTranslation whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TourTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TourTranslation whereLang($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TourTranslation whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TourTranslation whereTourId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TourTranslation whereUpdatedAt($value)
 */
	class TourTranslation extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Transaction
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $gateway
 * @property string|null $payment_type
 * @property string|null $additional_content
 * @property string|null $mpesa_request
 * @property string|null $mpesa_receipt
 * @property int $status
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction query()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereAdditionalContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereGateway($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereMpesaReceipt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereMpesaRequest($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction wherePaymentType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereUserId($value)
 */
	class Transaction extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Translation
 *
 * @property int $id
 * @property string|null $lang
 * @property string|null $lang_key
 * @property string|null $lang_value
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Translation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Translation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Translation query()
 * @method static \Illuminate\Database\Eloquent\Builder|Translation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Translation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Translation whereLang($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Translation whereLangKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Translation whereLangValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Translation whereUpdatedAt($value)
 */
	class Translation extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Unity
 *
 * @property int $id
 * @property array $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $default_unit
 * @property float $rate
 * @property-read mixed $translations
 * @method static \Illuminate\Database\Eloquent\Builder|Unity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Unity newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Unity query()
 * @method static \Illuminate\Database\Eloquent\Builder|Unity whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unity whereDefaultUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unity whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unity whereJsonContainsLocale(string $column, string $locale, ?mixed $value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unity whereJsonContainsLocales(string $column, array $locales, ?mixed $value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unity whereLocale(string $column, string $locale)
 * @method static \Illuminate\Database\Eloquent\Builder|Unity whereLocales(string $column, array $locales)
 * @method static \Illuminate\Database\Eloquent\Builder|Unity whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unity whereRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unity whereUpdatedAt($value)
 */
	class Unity extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Upload
 *
 * @property int $id
 * @property string|null $file_original_name
 * @property string|null $file_name
 * @property int|null $user_id
 * @property int|null $file_size
 * @property string|null $extension
 * @property string|null $type
 * @property string|null $external_link
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Upload newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Upload newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Upload onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Upload query()
 * @method static \Illuminate\Database\Eloquent\Builder|Upload whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upload whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upload whereExtension($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upload whereExternalLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upload whereFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upload whereFileOriginalName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upload whereFileSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upload whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upload whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upload whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upload whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upload withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Upload withoutTrashed()
 */
	class Upload extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\UploadProductCatalog
 *
 * @property int $id
 * @property int $catalog_id
 * @property string $path
 * @property string $extension
 * @property string|null $document_name
 * @property string $type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|UploadProductCatalog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UploadProductCatalog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UploadProductCatalog query()
 * @method static \Illuminate\Database\Eloquent\Builder|UploadProductCatalog whereCatalogId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadProductCatalog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadProductCatalog whereDocumentName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadProductCatalog whereExtension($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadProductCatalog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadProductCatalog wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadProductCatalog whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadProductCatalog whereUpdatedAt($value)
 */
	class UploadProductCatalog extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\UploadProducts
 *
 * @property int $id
 * @property int $id_product
 * @property string $path
 * @property string $extension
 * @property string|null $document_name
 * @property string $type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|UploadProducts newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UploadProducts newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UploadProducts query()
 * @method static \Illuminate\Database\Eloquent\Builder|UploadProducts whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadProducts whereDocumentName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadProducts whereExtension($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadProducts whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadProducts whereIdProduct($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadProducts wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadProducts whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadProducts whereUpdatedAt($value)
 */
	class UploadProducts extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property int|null $referred_by
 * @property string|null $provider
 * @property string|null $provider_id
 * @property string|null $refresh_token
 * @property string|null $access_token
 * @property string $user_type
 * @property string $name
 * @property string|null $email
 * @property string|null $email_verified_at
 * @property string|null $verification_code
 * @property string|null $new_email_verificiation_code
 * @property string|null $password
 * @property string|null $remember_token
 * @property string|null $device_token
 * @property string|null $avatar
 * @property string|null $avatar_original
 * @property string|null $address
 * @property string|null $country
 * @property string|null $state
 * @property string|null $city
 * @property string|null $postal_code
 * @property string|null $phone
 * @property float $balance
 * @property int $banned
 * @property string|null $referral_code
 * @property int|null $customer_package_id
 * @property int|null $remaining_uploads
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $steps
 * @property int|null $step_number
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $last_status_update
 * @property int|null $owner_id
 * @property \Illuminate\Support\Carbon|null $approved_at
 * @property int $first_login
 * @property int $tour
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Address> $addresses
 * @property-read int|null $addresses_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AffiliateLog> $affiliate_log
 * @property-read int|null $affiliate_log_count
 * @property-read \App\Models\AffiliateUser|null $affiliate_user
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AffiliateWithdrawRequest> $affiliate_withdraw_request
 * @property-read int|null $affiliate_withdraw_request_count
 * @property-read \App\Models\BusinessInformation|null $business_information
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Cart> $carts
 * @property-read int|null $carts_count
 * @property-read \App\Models\ClubPoint|null $club_point
 * @property-read \App\Models\ContactPerson|null $contact_people
 * @property-read \App\Models\Customer|null $customer
 * @property-read \App\Models\CustomerPackage|null $customer_package
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CustomerPackagePayment> $customer_package_payments
 * @property-read int|null $customer_package_payments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CustomerProduct> $customer_products
 * @property-read int|null $customer_products_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SellerLease> $leases
 * @property-read int|null $leases_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Order> $orders
 * @property-read int|null $orders_count
 * @property-read \App\Models\PayoutInformation|null $payout_information
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AuctionProductBid> $product_bids
 * @property-read int|null $product_bids_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ProductQuery> $product_queries
 * @property-read int|null $product_queries_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product> $products
 * @property-read int|null $products_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Review> $reviews
 * @property-read int|null $reviews_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \App\Models\Seller|null $seller
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Order> $seller_orders
 * @property-read int|null $seller_orders_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SellerPackagePayment> $seller_package_payments
 * @property-read int|null $seller_package_payments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OrderDetail> $seller_sales
 * @property-read int|null $seller_sales_count
 * @property-read \App\Models\Shop|null $shop
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Staff> $staff
 * @property-read int|null $staff_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Upload> $uploads
 * @property-read int|null $uploads_count
 * @property-read \App\Models\UserCoupon|null $userCoupon
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\VendorStatusHistory> $vendor_status_history
 * @property-read int|null $vendor_status_history_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Wallet> $wallets
 * @property-read int|null $wallets_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Warehouse> $warehouses
 * @property-read int|null $warehouses_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Wishlist> $wishlists
 * @property-read int|null $wishlists_count
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User role($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAccessToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereApprovedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAvatarOriginal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereBanned($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCustomerPackageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDeviceToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFirstLogin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastStatusUpdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereNewEmailVerificiationCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePostalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereProvider($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereProviderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereReferralCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereReferredBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRefreshToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRemainingUploads($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereStepNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereSteps($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTour($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUserType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereVerificationCode($value)
 */
	class User extends \Eloquent implements \Illuminate\Contracts\Auth\MustVerifyEmail {}
}

namespace App\Models{
/**
 * App\Models\UserCoupon
 *
 * @property int $user_id
 * @property int $coupon_id
 * @property string $coupon_code
 * @property float $min_buy
 * @property int $validation_days
 * @property float $discount
 * @property string $discount_type
 * @property int $expiry_date
 * @property-read \App\Models\Coupon|null $coupon
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|UserCoupon newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserCoupon newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserCoupon query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserCoupon whereCouponCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserCoupon whereCouponId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserCoupon whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserCoupon whereDiscountType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserCoupon whereExpiryDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserCoupon whereMinBuy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserCoupon whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserCoupon whereValidationDays($value)
 */
	class UserCoupon extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\VendorStatusHistory
 *
 * @property int $id
 * @property int $vendor_id
 * @property string $status
 * @property string|null $suspension_reason
 * @property string|null $details
 * @property string|null $reason
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $vendor
 * @method static \Illuminate\Database\Eloquent\Builder|VendorStatusHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VendorStatusHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VendorStatusHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder|VendorStatusHistory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VendorStatusHistory whereDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VendorStatusHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VendorStatusHistory whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VendorStatusHistory whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VendorStatusHistory whereSuspensionReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VendorStatusHistory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VendorStatusHistory whereVendorId($value)
 */
	class VendorStatusHistory extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\VerificationCode
 *
 * @property int $id
 * @property string $email
 * @property string $code
 * @property string $expires_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|VerificationCode newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VerificationCode newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VerificationCode query()
 * @method static \Illuminate\Database\Eloquent\Builder|VerificationCode whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VerificationCode whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VerificationCode whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VerificationCode whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VerificationCode whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VerificationCode whereUpdatedAt($value)
 */
	class VerificationCode extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Waitlist
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Waitlist newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Waitlist newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Waitlist query()
 */
	class Waitlist extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Wallet
 *
 * @property int $id
 * @property int $user_id
 * @property float $amount
 * @property string|null $payment_method
 * @property string|null $payment_details
 * @property int $approval
 * @property int $offline_payment
 * @property string|null $reciept
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet query()
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereApproval($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereOfflinePayment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet wherePaymentDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereReciept($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereUserId($value)
 */
	class Wallet extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Warehouse
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $warehouse_name
 * @property string|null $address_street
 * @property string|null $address_building
 * @property string|null $address_unit
 * @property int|null $emirate_id
 * @property int|null $area_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $saveasdraft
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse query()
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse whereAddressBuilding($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse whereAddressStreet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse whereAddressUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse whereAreaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse whereEmirateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse whereSaveasdraft($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse whereWarehouseName($value)
 */
	class Warehouse extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\WholesalePrice
 *
 * @property int $id
 * @property int $product_stock_id
 * @property int $min_qty
 * @property int $max_qty
 * @property float $price
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|WholesalePrice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WholesalePrice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WholesalePrice query()
 * @method static \Illuminate\Database\Eloquent\Builder|WholesalePrice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WholesalePrice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WholesalePrice whereMaxQty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WholesalePrice whereMinQty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WholesalePrice wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WholesalePrice whereProductStockId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WholesalePrice whereUpdatedAt($value)
 */
	class WholesalePrice extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Wishlist
 *
 * @property int $id
 * @property int $user_id
 * @property int $product_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\Product|null $product
 * @method static \Illuminate\Database\Eloquent\Builder|Wishlist newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Wishlist newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Wishlist query()
 * @method static \Illuminate\Database\Eloquent\Builder|Wishlist whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wishlist whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wishlist whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wishlist whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wishlist whereUserId($value)
 */
	class Wishlist extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Zone
 *
 * @property int $id
 * @property string $name
 * @property int $status 0 = Inactive, 1 = Active
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CarrierRangePrice> $carrier_range_prices
 * @property-read int|null $carrier_range_prices_count
 * @method static \Illuminate\Database\Eloquent\Builder|Zone newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Zone newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Zone query()
 * @method static \Illuminate\Database\Eloquent\Builder|Zone whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Zone whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Zone whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Zone whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Zone whereUpdatedAt($value)
 */
	class Zone extends \Eloquent {}
}

