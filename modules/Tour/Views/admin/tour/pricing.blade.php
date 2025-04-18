<div class="panel">

    <div class="panel-title"><strong>{{__("Pricing")}}</strong></div>

    <div class="panel-body">

        @if(is_default_lang())

        <h3 class="panel-body-title">{{__("Tour Price")}}</h3>

        <div class="row">

            <div class="col-lg-6">

                <div class="form-group">

                    <label class="control-label">{{__("Price")}}</label>

                    <input type="text" name="price" class="form-control" value="{{old('price',$row->price)}}"
                        placeholder="{{__(" Tour Price")}}">

                </div>

            </div>

            <div class="col-lg-6">

                <div class="form-group">

                    <label class="control-label">{{__("Sale Price")}}</label>

                    <input type="text" name="sale_price" class="form-control"
                        value="{{old('sale_price',$row->sale_price)}}" placeholder="{{__(" Tour Sale Price")}}">

                </div>

            </div>

            <div class="col-lg-12">

                <span>

                    {{__("If the regular price is less than the discount , it will show the regular price")}}

                </span>

            </div>

        </div>

        <hr>

        @endif

        @if(is_default_lang())

        <h3 class="panel-body-title">{{__('Person Types')}}</h3>

        <div class="form-group">

            <label><input type="checkbox" name="enable_person_types" @if(!empty($row->meta->enable_person_types))
                checked @endif value="1"> {{__('Enable Person Types')}}

            </label>

        </div>

        <div class="form-group-item" data-condition="enable_person_types:is(1)">

            <label class="control-label">{{__('Person Types')}}</label>

            <div class="g-items-header">

                <div class="row">

                    <div class="col-md-5">{{__("Person Type")}}</div>

                    <div class="col-md-2">{{__('Min')}}</div>

                    <div class="col-md-2">{{__('Max')}}</div>

                    <div class="col-md-2">{{__('Price')}}</div>

                    <div class="col-md-1"></div>

                </div>

            </div>

            <div class="g-items">

                <?php  $languages = \Modules\Language\Models\Language::getActive();  ?>

                @if(!empty($person_types = old('person_types',$row->meta->person_types ?? "")))

                @foreach($person_types as $key=>$person_type)

                <div class="item" data-number="{{$key}}">

                    <div class="row">

                        <div class="col-md-5">

                            @if(!empty($languages) && setting_item('site_enable_multi_lang') &&
                            setting_item('site_locale'))

                            @foreach($languages as $language)

                            <?php $key_lang = setting_item('site_locale') != $language->locale ? "_".$language->locale : ""   ?>

                            <div class="g-lang">

                                <div class="title-lang">{{$language->name}}</div>

                                <input type="text" name="person_types[{{$key}}][name{{$key_lang}}]" class="form-control"
                                    value="{{$person_type['name'.$key_lang] ?? ''}}" placeholder="{{__('Eg: Adults')}}">

                                <input type="text" name="person_types[{{$key}}][desc{{$key_lang}}]" class="form-control"
                                    value="{{$person_type['desc'.$key_lang] ?? ''}}"
                                    placeholder="{{__('Description')}}">

                            </div>

                            @endforeach

                            @else

                            <input type="text" name="person_types[{{$key}}][name]" class="form-control"
                                value="{{$person_type['name'] ?? ''}}" placeholder="{{__('Eg: Adults')}}">

                            <input type="text" name="person_types[{{$key}}][desc]" class="form-control"
                                value="{{$person_type['desc'] ?? ''}}" placeholder="{{__('Description')}}">

                            @endif

                        </div>

                        <div class="col-md-2">

                            <input type="number" min="0" name="person_types[{{$key}}][min]" class="form-control"
                                value="{{$person_type['min'] ?? 0}}" placeholder="{{__(" Minimum per booking")}}">

                        </div>

                        <div class="col-md-2">

                            <input type="number" min="0" name="person_types[{{$key}}][max]" class="form-control"
                                value="{{$person_type['max'] ?? 0}}" placeholder="{{__(" Maximum per booking")}}">

                        </div>

                        <div class="col-md-2">

                            <input type="text" min="0" name="person_types[{{$key}}][price]" class="form-control"
                                value="{{$person_type['price'] ?? 0}}" placeholder="{{__(" per 1 item")}}">

                        </div>

                        <div class="col-md-1">

                            <span class="btn btn-danger btn-sm btn-remove-item"><i class="fa fa-trash"></i></span>

                        </div>

                    </div>

                </div>

                @endforeach

                @endif

            </div>

            <div class="text-right">

                <span class="btn btn-info btn-sm btn-add-item"><i class="icon ion-ios-add-circle-outline"></i> {{__('Add
                    item')}}</span>

            </div>

            <div class="g-more hide">

                <div class="item" data-number="__number__">

                    <div class="row">

                        <div class="col-md-5">

                            @if(!empty($languages) && setting_item('site_enable_multi_lang') &&
                            setting_item('site_locale'))

                            @foreach($languages as $language)

                            <?php $key = setting_item('site_locale') != $language->locale ? "_".$language->locale : ""   ?>

                            <div class="g-lang">

                                <div class="title-lang">{{$language->name}}</div>

                                <input type="text" __name__="person_types[__number__][name{{$key}}]"
                                    class="form-control" value="" placeholder="{{__('Eg: Adults')}}">

                                <input type="text" __name__="person_types[__number__][desc{{$key}}]"
                                    class="form-control" value="" placeholder="{{__('Description')}}">

                            </div>

                            @endforeach

                            @else

                            <input type="text" __name__="person_types[__number__][name]" class="form-control" value=""
                                placeholder="{{__('Eg: Adults')}}">

                            <input type="text" __name__="person_types[__number__][desc]" class="form-control" value=""
                                placeholder="{{__('Description')}}">

                            @endif

                        </div>

                        <div class="col-md-2">

                            <input type="number" min="0" __name__="person_types[__number__][min]" class="form-control"
                                value="" placeholder="{{__(" Minimum per booking")}}">

                        </div>

                        <div class="col-md-2">

                            <input type="number" min="0" __name__="person_types[__number__][max]" class="form-control"
                                value="" placeholder="{{__(" Maximum per booking")}}">

                        </div>

                        <div class="col-md-2">

                            <input type="text" min="0" __name__="person_types[__number__][price]" class="form-control"
                                value="" placeholder="{{__(" per 1 item")}}">

                        </div>

                        <div class="col-md-1">

                            <span class="btn btn-danger btn-sm btn-remove-item"><i class="fa fa-trash"></i></span>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        @endif

        @if(is_default_lang())
        <h3 class="panel-body-title">{{__('Packages')}}</h3>

        <div class="form-group">
            <label>
                <input type="checkbox" name="enable_packages" @if(!empty($row->meta->enable_packages)) checked @endif
                value="1">
                {{__('Enable Packages')}}
            </label>
        </div>

        <div class="form-group-item" data-condition="enable_packages:is(1)">
            <label class="control-label">{{__('Package Types')}}</label>

            <div class="g-items">
                @if(!empty($packages = old('packages', $row->meta->packages ?? "")))
                @foreach($packages as $key => $package)
                <div class="item" data-number="{{ $key }}">
                    <div class="row">
                        <!-- Package Name & Description -->
                        <div class="col-md-5">
                            <input type="text" name="packages[{{$key}}][name]" class="form-control"
                                value="{{ $package['name'] ?? '' }}" placeholder="{{__('Package Name')}}">
                            <textarea name="packages[{{$key}}][description]" class="form-control"
                                placeholder="{{__('Package Description')}}">{{ $package['description'] ?? '' }}</textarea>
                        </div>
                        <!-- Price -->
                        <div class="col-md-2">
                            <input type="text" name="packages[{{$key}}][price]" class="form-control"
                                value="{{ $package['price'] ?? '' }}" placeholder="{{__('Price per Person')}}">
                        </div>
                        <!-- Time Slots -->
                        <div class="col-md-3">
                            <div class="time-slots">
                                @if(!empty($package['start_times']))
                                @foreach($package['start_times'] as $timeKey => $startTime)
                                <div class="time-slot" data-time-key="{{ $timeKey }}">
                                    <input type="time" name="packages[{{$key}}][start_times][{{$timeKey}}]"
                                        class="form-control" value="{{ $startTime }}"
                                        placeholder="{{__('Tour Start Time')}}">
                                    <span class="btn btn-danger btn-sm btn-remove-time">
                                        <i class="fa fa-trash"></i>
                                    </span>
                                </div>
                                @endforeach
                                @else
                                <div class="time-slot" data-time-key="0">
                                    <input type="time" name="packages[{{$key}}][start_times][0]" class="form-control"
                                        placeholder="{{__('Tour Start Time')}}">
                                    <span class="btn btn-danger btn-sm btn-remove-time">
                                        <i class="fa fa-trash"></i>
                                    </span>
                                </div>
                                @endif
                            </div>
                            <span class="btn btn-info btn-sm btn-add-time">{{__('Add Time')}}</span>
                        </div>
                        <!-- Remove Package -->
                        <div class="col-md-2">
                            <span class="btn btn-danger btn-sm btn-remove-item">
                                <i class="fa fa-trash"></i>
                            </span>
                        </div>
                    </div>
                </div>
                @endforeach
                @endif
            </div>

            <!-- Add New Package Button -->
            <div class="text-right">
                <span class="btn btn-info btn-sm btn-add-item">
                    <i class="icon ion-ios-add-circle-outline"></i> {{__('Add Package')}}
                </span>
            </div>

            <!-- Hidden Template for New Package -->
            <div class="g-more hide">
                <div class="item" data-number="__number__">
                    <div class="row">
                        <div class="col-md-5">
                            <input type="text" name="packages[__number__][name]" class="form-control" value=""
                                placeholder="{{__('Package Name')}}">
                            <textarea name="packages[__number__][description]" class="form-control"
                                placeholder="{{__('Package Description')}}"></textarea>
                        </div>
                        <div class="col-md-2">
                            <input type="text" name="packages[__number__][price]" class="form-control" value=""
                                placeholder="{{__('Price per Person')}}">
                        </div>
                        <div class="col-md-3">
                            <div class="time-slots">
                                <div class="time-slot" data-time-key="0">
                                    <input type="time" name="packages[__number__][start_times][0]" class="form-control"
                                        placeholder="{{__('Tour Start Time')}}">
                                    <span class="btn btn-danger btn-sm btn-remove-time">
                                        <i class="fa fa-trash"></i>
                                    </span>
                                </div>
                            </div>
                            <span class="btn btn-info btn-sm btn-add-time">{{__('Add Time')}}</span>
                        </div>
                        <div class="col-md-2">
                            <span class="btn btn-danger btn-sm btn-remove-item">
                                <i class="fa fa-trash"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <script src="https://code.jquery.com/jquery-3.7.1.min.js"
            integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <script>
            $(document).ready(function () {
                // Add new time slot
                $(document).on('click', '.btn-add-time', function () {
                    const $packageItem = $(this).closest('.item');
                    const $timeSlotsWrapper = $packageItem.find('.time-slots');
                    const timeKey = Date.now(); // Unique identifier for the time slot

                    const newTimeSlot = `
                        <div class="time-slot" data-time-key="${timeKey}">
                            <input type="time" name="packages[${$packageItem.data('number')}][start_times][${timeKey}]" 
                                class="form-control" placeholder="Tour Start Time">
                            <span class="btn btn-danger btn-sm btn-remove-time"><i class="fa fa-trash"></i></span>
                        </div>
                    `;
                    $timeSlotsWrapper.append(newTimeSlot);
                });

                // Remove time slot
                $(document).on('click', '.btn-remove-time', function () {
                    $(this).closest('.time-slot').remove();
                });
            });
        </script>

        @endif



        @if(is_default_lang())

        <hr>

        <h3 class="panel-body-title app_get_locale">{{__('Extra Price')}}</h3>

        <div class="form-group app_get_locale">

            <label><input type="checkbox" name="enable_extra_price" @if(!empty($row->meta->enable_extra_price)) checked
                @endif value="1"> {{__('Enable extra price')}}

            </label>

        </div>

        <div class="form-group-item" data-condition="enable_extra_price:is(1)">

            <label class="control-label">{{__('Extra Price')}}</label>

            <div class="g-items-header">

                <div class="row">

                    <div class="col-md-5">{{__("Name")}}</div>

                    <div class="col-md-3">{{__('Price')}}</div>

                    <div class="col-md-3">{{__('Type')}}</div>

                    <div class="col-md-1"></div>

                </div>

            </div>

            <div class="g-items">

                @if(!empty($extra_prices = old('extra_price',$row->meta->extra_price ?? "")))

                @foreach($extra_prices as $key=>$extra_price)

                <div class="item" data-number="{{$key}}">

                    <div class="row">

                        <div class="col-md-5">

                            @if(!empty($languages) && setting_item('site_enable_multi_lang') &&
                            setting_item('site_locale'))

                            @foreach($languages as $language)

                            <?php $key_lang = setting_item('site_locale') != $language->locale ? "_".$language->locale : ""   ?>

                            <div class="g-lang">

                                <div class="title-lang">{{$language->name}}</div>

                                <input type="text" name="extra_price[{{$key}}][name{{$key_lang}}]" class="form-control"
                                    value="{{$extra_price['name'.$key_lang] ?? ''}}"
                                    placeholder="{{__('Extra price name')}}">

                            </div>

                            @endforeach

                            @else

                            <input type="text" name="extra_price[{{$key}}][name]" class="form-control"
                                value="{{$extra_price['name'] ?? ''}}" placeholder="{{__('Extra price name')}}">

                            @endif

                        </div>

                        <div class="col-md-3">

                            <input type="text" min="0" name="extra_price[{{$key}}][price]" class="form-control"
                                value="{{$extra_price['price']}}">

                        </div>

                        <div class="col-md-3">

                            <select name="extra_price[{{$key}}][type]" class="form-control">

                                <option @if($extra_price['type']=='one_time' ) selected @endif value="one_time">
                                    {{__("One-time")}}</option>

                                <option @if($extra_price['type']=='per_hour' ) selected @endif value="per_hour">
                                    {{__("Per hour")}}</option>

                                <option @if($extra_price['type']=='per_day' ) selected @endif value="per_day">{{__("Per
                                    day")}}</option>

                            </select>



                            <label>

                                <input type="checkbox" min="0" name="extra_price[{{$key}}][per_person]" value="on"
                                    @if($extra_price['per_person'] ?? '' ) checked @endif>

                                {{__("Price per person")}}

                            </label>

                        </div>

                        <div class="col-md-1">

                            <span class="btn btn-danger btn-sm btn-remove-item"><i class="fa fa-trash"></i></span>

                        </div>

                    </div>

                </div>

                @endforeach

                @endif

            </div>

            <div class="text-right">

                <span class="btn btn-info btn-sm btn-add-item"><i class="icon ion-ios-add-circle-outline"></i> {{__('Add
                    item')}}</span>

            </div>

            <div class="g-more hide">

                <div class="item" data-number="__number__">

                    <div class="row">

                        <div class="col-md-5">

                            @if(!empty($languages) && setting_item('site_enable_multi_lang') &&
                            setting_item('site_locale'))

                            @foreach($languages as $language)

                            <?php $key = setting_item('site_locale') != $language->locale ? "_".$language->locale : ""   ?>

                            <div class="g-lang">

                                <div class="title-lang">{{$language->name}}</div>

                                <input type="text" __name__="extra_price[__number__][name{{$key}}]" class="form-control"
                                    value="" placeholder="{{__('Extra price name')}}">

                            </div>

                            @endforeach

                            @else

                            <input type="text" __name__="extra_price[__number__][name]" class="form-control" value=""
                                placeholder="{{__('Extra price name')}}">

                            @endif

                        </div>

                        <div class="col-md-3">

                            <input type="text" min="0" __name__="extra_price[__number__][price]" class="form-control"
                                value="">

                        </div>

                        <div class="col-md-3">

                            <select __name__="extra_price[__number__][type]" class="form-control">

                                <option value="one_time">{{__("One-time")}}</option>

                                <option value="per_hour">{{__("Per hour")}}</option>

                                <option value="per_day">{{__("Per day")}}</option>

                            </select>



                            <label>

                                <input type="checkbox" min="0" __name__="extra_price[__number__][per_person]"
                                    value="on">

                                {{__("Price per person")}}

                            </label>

                        </div>

                        <div class="col-md-1">

                            <span class="btn btn-danger btn-sm btn-remove-item"><i class="fa fa-trash"></i></span>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        @endif



        @if(is_default_lang())

        <hr>

        <h3 class="panel-body-title">{{__('Discount by number of people')}}</h3>

        <div class="form-group-item">

            <div class="g-items-header">

                <div class="row">

                    <div class="col-md-4">{{__("No of people")}}</div>

                    <div class="col-md-3">{{__('Discount')}}</div>

                    <div class="col-md-3">{{__('Type')}}</div>

                    <div class="col-md-1"></div>

                </div>

            </div>

            <div class="g-items">

                @if(!empty($discount_by_people = old('discount_by_people',$row->meta->discount_by_people ?? "")))

                @foreach($discount_by_people as $key=>$item)

                <div class="item" data-number="{{$key}}">

                    <div class="row">

                        <div class="col-md-2">

                            <input type="number" min="0" name="discount_by_people[{{$key}}][from]" class="form-control"
                                value="{{$item['from']}}" placeholder="{{__('From')}}">

                        </div>

                        <div class="col-md-2">

                            <input type="number" min="0" name="discount_by_people[{{$key}}][to]" class="form-control"
                                value="{{$item['to']}}" placeholder="{{__('To')}}">

                        </div>

                        <div class="col-md-3">

                            <input type="number" min="0" name="discount_by_people[{{$key}}][amount]"
                                class="form-control" value="{{$item['amount']}}">

                        </div>

                        <div class="col-md-3">

                            <select name="discount_by_people[{{$key}}][type]" class="form-control">

                                <option @if($item['type']=='fixed' ) selected @endif value="fixed">{{__("Fixed")}}
                                </option>

                                <option @if($item['type']=='percent' ) selected @endif value="percent">{{__("Percent
                                    (%)")}}</option>

                            </select>

                        </div>

                        <div class="col-md-1">

                            <span class="btn btn-danger btn-sm btn-remove-item"><i class="fa fa-trash"></i></span>

                        </div>

                    </div>

                </div>

                @endforeach

                @endif

            </div>

            <div class="text-right">

                <span class="btn btn-info btn-sm btn-add-item"><i class="icon ion-ios-add-circle-outline"></i> {{__('Add
                    item')}}</span>

            </div>

            <div class="g-more hide">

                <div class="item" data-number="__number__">

                    <div class="row">

                        <div class="col-md-2">

                            <input type="number" min="0" __name__="discount_by_people[__number__][from]"
                                class="form-control" value="" placeholder="{{__('From')}}">

                        </div>

                        <div class="col-md-2">

                            <input type="number" min="0" __name__="discount_by_people[__number__][to]"
                                class="form-control" value="" placeholder="{{__('To')}}">

                        </div>

                        <div class="col-md-3">

                            <input type="number" min="0" __name__="discount_by_people[__number__][amount]"
                                class="form-control" value="">

                        </div>

                        <div class="col-md-3">

                            <select __name__="discount_by_people[__number__][type]" class="form-control">

                                <option value="fixed">{{__("Fixed")}}</option>

                                <option value="percent">{{__("Percent")}}</option>

                            </select>

                        </div>

                        <div class="col-md-1">

                            <span class="btn btn-danger btn-sm btn-remove-item"><i class="fa fa-trash"></i></span>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        @endif



        @if(is_default_lang() and (!empty(setting_item("tour_allow_vendor_can_add_service_fee")) or is_admin()))

        <hr>

        <h3 class="panel-body-title app_get_locale">{{__('Service fee')}}</h3>

        <div class="form-group app_get_locale">

            <label><input type="checkbox" name="enable_service_fee" @if(!empty($row->enable_service_fee)) checked @endif
                value="1"> {{__('Enable service fee')}}

            </label>

        </div>

        <div class="form-group-item" data-condition="enable_service_fee:is(1)">

            <label class="control-label">{{__('Buyer Fees')}}</label>

            <div class="g-items-header">

                <div class="row">

                    <div class="col-md-5">{{__("Name")}}</div>

                    <div class="col-md-3">{{__('Price')}}</div>

                    <div class="col-md-3">{{__('Type')}}</div>

                    <div class="col-md-1"></div>

                </div>

            </div>

            <div class="g-items">

                <?php  $languages = \Modules\Language\Models\Language::getActive();?>

                @if(!empty($service_fee = old('service_fee',$row->service_fee)))

                @foreach($service_fee as $key=>$item)

                <div class="item" data-number="{{$key}}">

                    <div class="row">

                        <div class="col-md-5">

                            @if(!empty($languages) && setting_item('site_enable_multi_lang') &&
                            setting_item('site_locale'))

                            @foreach($languages as $language)

                            <?php $key_lang = setting_item('site_locale') != $language->locale ? "_".$language->locale : ""   ?>

                            <div class="g-lang">

                                <div class="title-lang">{{$language->name}}</div>

                                <input type="text" name="service_fee[{{$key}}][name{{$key_lang}}]" class="form-control"
                                    value="{{$item['name'.$key_lang] ?? ''}}" placeholder="{{__('Fee name')}}">

                                <input type="text" name="service_fee[{{$key}}][desc{{$key_lang}}]" class="form-control"
                                    value="{{$item['desc'.$key_lang] ?? ''}}" placeholder="{{__('Fee desc')}}">

                            </div>



                            @endforeach

                            @else

                            <input type="text" name="service_fee[{{$key}}][name]" class="form-control"
                                value="{{$item['name'] ?? ''}}" placeholder="{{__('Fee name')}}">

                            <input type="text" name="service_fee[{{$key}}][desc]" class="form-control"
                                value="{{$item['desc'] ?? ''}}" placeholder="{{__('Fee desc')}}">

                            @endif

                        </div>

                        <div class="col-md-3">

                            <input type="number" min="0" step="0.1" name="service_fee[{{$key}}][price]"
                                class="form-control" value="{{$item['price'] ?? ""}}">

                            <select name="service_fee[{{$key}}][unit]" class="form-control">

                                <option @if(($item['unit'] ?? "" )=='fixed' ) selected @endif value="fixed">{{
                                    __("Fixed") }}</option>

                                <option @if(($item['unit'] ?? "" )=='percent' ) selected @endif value="percent">{{
                                    __("Percent") }}</option>

                            </select>

                        </div>

                        <div class="col-md-3">

                            <select name="service_fee[{{$key}}][type]" class="form-control d-none">

                                <option @if($item['type'] ?? ""=='one_time' ) selected @endif value="one_time">
                                    {{__("One-time")}}</option>

                            </select>

                            <label>

                                <input type="checkbox" min="0" name="service_fee[{{$key}}][per_person]" value="on"
                                    @if($item['per_person'] ?? '' ) checked @endif>

                                {{__("Price per person")}}

                            </label>

                        </div>

                        <div class="col-md-1">

                            <span class="btn btn-danger btn-sm btn-remove-item"><i class="fa fa-trash"></i></span>

                        </div>

                    </div>

                </div>

                @endforeach

                @endif

            </div>

            <div class="text-right">

                <span class="btn btn-info btn-sm btn-add-item"><i class="icon ion-ios-add-circle-outline"></i> {{__('Add
                    item')}}</span>

            </div>

            <div class="g-more hide">

                <div class="item" data-number="__number__">

                    <div class="row">

                        <div class="col-md-5">

                            @if(!empty($languages) && setting_item('site_enable_multi_lang') &&
                            setting_item('site_locale'))

                            @foreach($languages as $language)

                            <?php $key = setting_item('site_locale') != $language->locale ? "_".$language->locale : ""   ?>

                            <div class="g-lang">

                                <div class="title-lang">{{$language->name}}</div>

                                <input type="text" __name__="service_fee[__number__][name{{$key}}]" class="form-control"
                                    value="" placeholder="{{__('Fee name')}}">

                                <input type="text" __name__="service_fee[__number__][desc{{$key}}]" class="form-control"
                                    value="" placeholder="{{__('Fee desc')}}">

                            </div>



                            @endforeach

                            @else

                            <input type="text" __name__="service_fee[__number__][name]" class="form-control" value=""
                                placeholder="{{__('Fee name')}}">

                            <input type="text" __name__="service_fee[__number__][desc]" class="form-control" value=""
                                placeholder="{{__('Fee desc')}}">

                            @endif

                        </div>

                        <div class="col-md-3">

                            <input type="number" min="0" step="0.1" __name__="service_fee[__number__][price]"
                                class="form-control" value="">

                            <select __name__="service_fee[__number__][unit]" class="form-control">

                                <option value="fixed">{{ __("Fixed") }}</option>

                                <option value="percent">{{ __("Percent") }}</option>

                            </select>

                        </div>

                        <div class="col-md-3">

                            <select __name__="service_fee[__number__][type]" class="form-control d-none">

                                <option value="one_time">{{__("One-time")}}</option>

                            </select>

                            <label>

                                <input type="checkbox" min="0" __name__="service_fee[__number__][per_person]"
                                    value="on">

                                {{__("Price per person")}}

                            </label>

                        </div>

                        <div class="col-md-1">

                            <span class="btn btn-danger btn-sm btn-remove-item"><i class="fa fa-trash"></i></span>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        @endif

    </div>

</div>