<div class="bravo_single_book_wrap @if(setting_item('tour_enable_inbox')) has-vendor-box @endif">

    <div class="bravo_single_book">

        <div id="bravo_tour_book_app" v-cloak>

            @if($row->discount_percent)

            <div class="tour-sale-box">

                <span class="sale_class box_sale sale_small">{{$row->discount_percent}}</span>

            </div>

            @endif
            <div class="form-head">

                <div class="price">

                    <span class="label">

                        {{__("from")}}

                    </span>

                    <span class="value">

                        <span class="onsale">{{ $row->display_sale_price }}</span>

                        <span class="text-lg">{{ $row->display_price }}</span>

                    </span>

                </div>

            </div>

            @if($row->category_id == 9 || $row->price == null)
            <div class="nav-enquiry" v-if="is_form_enquiry_and_book">

                <div class="enquiry-item ">

                    <span>{{ __("Book") }}</span>

                </div>

                <div class="enquiry-item active">

                    <span>{{ __("Enquiry") }}</span>

                </div>

            </div>

            @else

            <div class="nav-enquiry" v-if="is_form_enquiry_and_book">

                <div class="enquiry-item active">

                    <span>{{ __("Book") }}</span>

                </div>

                <div class="enquiry-item" data-toggle="modal">

                    <span>{{ __("Enquiry") }}</span>

                </div>

            </div>

            @endif
            @php $user = auth()->user(); @endphp
            @if($row->category_id == 9 || $row->price == null)

            <div class="form-enquiry" :class="{'d-none':enquiry_type!='book'}">


                <div class="form-content">

                    <div class="form-group form-date-field form-date-search clearfix " data-format="{{get_moment_date_format()}}">

                        <input type="hidden" name="service_id" value="{{$row->id}}">

                        <input type="hidden" name="service_type" value="{{$service_type ?? 'tour'}}">

                        <div class="form-group">

                            <input type="text" class="form-control" value="{{$user->display_name ?? ''}}" name="enquiry_name" placeholder="{{ __("Name *") }}">

                        </div>

                        <div class="form-group">

                            <input type="text" class="form-control" value="{{$user->email ?? ''}}" name="enquiry_email" placeholder="{{ __("Email *") }}">

                        </div>

                        <div class="form-group">

                            <input type="text" id="travel_from" value="{{$user->travel_from ?? ''}}" name="enquiry_travel_from" class="form-control" placeholder="{{ __("Travel From *") }}">

                        </div>

                        <div class="form-group">
                            <label for="num_people">Number of People</label>
                            <div class="d-flex">
                                <div class="mr-2">
                                    <label for="adult">Adults</label>
                                    <input type="number" id="adult" value="{{$user->adult ?? ''}}" name="enquiry_adult" class="form-control" min="0">
                                </div>
                                <div>
                                    <label for="children">Children</label>
                                    <input type="number" id="children" value="{{$user->children ?? ''}}" name="enquiry_children" class="form-control" min="0">
                                </div>
                            </div>
                        </div>

                        <div class="form-group" v-if="!enquiry_is_submit">

                            <input type="text" class="form-control" value="{{$user->phone ?? ''}}" name="enquiry_phone" placeholder="{{ __("Phone") }}">

                        </div>

                        <div class="form-group" v-if="!enquiry_is_submit">

                            <textarea class="form-control" placeholder="{{ __("Note") }}" name="enquiry_note"></textarea>

                        </div>

                        <!-- @if(setting_item("booking_enquiry_enable_recaptcha")) -->

                        <!-- <div class="form-group">

                        {{recaptcha_field('enquiry_form')}}

                    </div> -->

                        <!-- @endif -->

                        <div class="message_box"></div>

                        <button type="button" class="btn btn-primary btn-submit-new-enquiry">{{__("Send now")}}

                            <i class="fa icon-loading fa-spinner fa-spin fa-fw" style="display: none"></i>

                        </button>

                    </div>
                </div>

            </div>
            @else
            <div class="form-book" :class="{'d-none':enquiry_type!='book'}">

                <div class="form-content">

                    <div class="form-group form-date-field form-date-search clearfix " data-format="{{get_moment_date_format()}}">

                        <div class="d-flex p-2  flex-wrap clearfix text-center" v-if="is_fixed_date">

                            <div class="w-50 py-3 flex-grow-1">

                                <div class="font-weight-bold">{{__("Tour Start Date")}}</div>

                                <span>@{{ start_date_html }}</span>

                            </div>

                            <div class="w-50 py-3 flex-grow-1 border-left">

                                <div class="font-weight-bold">{{__("Tour End Date")}}</div>

                                <span>@{{ end_date_html }}</span>

                            </div>

                            <div class="w-100 py-3 flex-grow-1 border-top">

                                <div class="font-weight-bold">{{__("Last Booking Date")}}</div>

                                <span>@{{ last_booking_date_html }}</span>

                            </div>

                        </div>

                        <div class="date-wrapper clearfix" @click="openStartDate" v-else>

                            <div class="check-in-wrapper">

                                <label>{{__("Start Date")}}</label>

                                <div class="render check-in-render">@{{start_date_html}}</div>

                            </div>

                            <i class="fa fa-angle-down arrow"></i>

                        </div>

                        <input type="text" class="start_date" ref="start_date" style="height: 1px; visibility: hidden">

                    </div>

                    <div class="" v-if="person_types">

                        <div class="form-group form-guest-search" v-for="(type,index) in person_types">

                            <div class="guest-wrapper d-flex justify-content-between align-items-center">

                                <div class="flex-grow-1">

                                    <label>@{{type.name}}</label>

                                    <div class="render check-in-render">@{{type.desc}}</div>

                                    <div class="render check-in-render">@{{type.display_price}} {{__("per person")}}</div>

                                </div>

                                <div class="flex-shrink-0">

                                    <div class="input-number-group">

                                        <i class="icon ion-ios-remove-circle-outline" @click="minusPersonType(type)"></i>

                                        <span class="input"><input type="number" v-model="type.number" min="1" @change="changePersonType(type)" /></span>

                                        <i class="icon ion-ios-add-circle-outline" @click="addPersonType(type)"></i>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="form-group form-guest-search" v-else>

                        <div class="guest-wrapper d-flex justify-content-between align-items-center">

                            <div class="flex-grow-1">

                                <label>{{__("Guests")}}</label>

                            </div>

                            <div class="flex-shrink-0">

                                <div class="input-number-group">

                                    <i class="icon ion-ios-remove-circle-outline" @click="minusGuestsType()"></i>

                                    <span class="input"><input type="number" v-model="guests" min="0" /></span>

                                    <i class="icon ion-ios-add-circle-outline" @click="addGuestsType()"></i>

                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="form-section-group form-group" v-if="packages.length">
                        <h4 class="form-section-title">{{ __('Packages:') }}</h4>

                        <div class="form-group" v-for="(type, index) in packages" :key="index">
                            <div class="extra-price-wrap d-flex justify-content-between">
                                <!-- Checkbox and Package Name -->
                                <div class="flex-grow-1">
                                    <label>
                                        <input type="radio" name="package" value="0" v-model="type.enable">
                                        @{{ type.name }}
                                    </label>
                                    <div class="render" v-if="type.price">(@{{ type.display_price }})</div>
                                </div>

                                <!-- Start Times as Radio Buttons -->
                                <div class="flex-shrink-0">
                                    <div v-if="type.start_times && type.start_times.length">
                                        <div v-for="(time, timeIndex) in type.start_times" :key="timeIndex">
                                            <label>
                                                <input
                                                    type="radio"
                                                    :name="'start_time_' + index"
                                                    :value="time"
                                                    v-model="type.selected_time">
                                                @{{ time }}
                                            </label>
                                        </div>
                                    </div>
                                    <div v-else>
                                        {{ __('No start times available') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-section-group form-group" v-if="extra_price.length">

                        <h4 class="form-section-title">{{__('Extra prices:')}}</h4>

                        <div class="form-group" v-for="(type,index) in extra_price">

                            <div class="extra-price-wrap d-flex justify-content-between">

                                <div class="flex-grow-1">

                                    <label><input type="checkbox" true-value="1" false-value="0" v-model="type.enable"> @{{type.name}}</label>

                                    <div class="render" v-if="type.price_type">(@{{type.price_type}})</div>

                                </div>

                                <div class="flex-shrink-0">@{{type.price_html}}</div>

                            </div>

                        </div>

                    </div>

                    <div class="form-section-group form-group" v-if="discount_by_people_output.length">

                        <h4 class="form-section-title">{{__('Discounts:')}}</h4>

                        <div class="extra-price-wrap d-flex justify-content-between" v-for="(type,index) in discount_by_people_output">

                            <div class="flex-grow-1">

                                <span class="render" v-if='type.to'>

                                    @{{type.from}} - @{{type.to}} {{__('guests')}}

                                </span>

                                <span class="render" v-else>

                                    {{__('from')}} @{{type.from}} {{__('guests')}}

                                </span>

                            </div>

                            <div class="flex-shrink-0">

                                <div class="unit">

                                    - @{{ formatMoney(type.total) }}

                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="form-section-group form-group-padding" v-if="buyer_fees.length">

                        <div class="extra-price-wrap d-flex justify-content-between" v-for="(type,index) in buyer_fees">

                            <div class="flex-grow-1">

                                <label>@{{type.type_name}}

                                    <i class="icofont-info-circle" v-if="type.desc" data-toggle="tooltip" data-placement="top" :title="type.type_desc"></i>

                                </label>

                                <div class="render" v-if="type.price_type">(@{{type.price_type}})</div>

                            </div>

                            <div class="flex-shrink-0">

                                <div class="unit" v-if='type.unit == "percent"'>

                                    @{{ type.price }}%

                                </div>

                                <div class="unit" v-else>

                                    @{{ formatMoney(type.price) }}

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <ul class="form-section-total list-unstyled" v-if="total_price > 0">

                    <li>

                        <label>{{__("Total")}}</label>

                        <span class="price">@{{total_price_html}}</span>

                    </li>

                    <li v-if="is_deposit_ready">

                        <label for="">{{__("Pay now")}}</label>

                        <span class="price">@{{pay_now_price_html}}</span>

                    </li>

                </ul>

                <div v-html="html"></div>

                <div class="submit-group">

                    <a class="btn btn-large" @click="doSubmit($event)" :class="{'disabled':onSubmit,'btn-success':(step == 2),'btn-primary':step == 1}" name="submit">

                        <span>{{__("BOOK NOW")}}</span>

                        <i v-show="onSubmit" class="fa fa-spinner fa-spin"></i>

                    </a>

                    <div class="alert-text mt10" v-show="message.content" v-html="message.content" :class="{'danger':!message.type,'success':message.type}"></div>

                </div>

            </div>
            @endif
            <div class="form-send-enquiry" :class="{'d-none':enquiry_type!='book'}">
                <div class="form-send-enquiry" v-show="enquiry_type=='enquiry'">

                    <button class="btn btn-primary" data-toggle="modal" data-target="#enquiry_form_modal">

                        {{ __("Contact Now") }}

                    </button>

                </div>

            </div>

        </div>

    </div>

    <!-- @include("Booking::frontend.global.enquiry-form",['service_type'=>'tour']) -->
    @push('after_scripts')
    <script>
        $('.btn-submit-new-enquiry').click(function(e) {
            e.preventDefault();
            let form = $(this).closest('.form-enquiry');
            console.log("sending enquiry");

            $.ajax({
                url: bookingCore.url + '/booking/addEnquiry',
                data: form.find('textarea,input,select').serialize(),
                dataType: 'json',
                type: 'post',
                beforeSend: function() {
                    form.find('.message_box').html('').hide();
                    form.find('.icon-loading').css("display", 'inline-block');
                },
                success: function(res) {
                    if (res.errors) {
                        res.message = '';
                        for (var k in res.errors) {
                            res.message += res.errors[k].join('<br>') + '<br>';
                        }
                    }
                    if (res.message) {
                        if (!res.status) {
                            form.find('.message_box').append('<div class="text text-danger">' + res.message + '</div>').show();
                        } else {
                            form.find('.message_box').append('<div class="text text-success">' + res.message + '</div>').show();
                        }
                    }

                    form.find('.icon-loading').hide();

                    if (res.status) {
                        form.find('textarea').val('');
                    }

                    if (typeof BravoReCaptcha != "undefined") {
                        BravoReCaptcha.reset('enquiry_form');
                    }
                },
                error: function(e) {
                    if (typeof BravoReCaptcha != "undefined") {
                        BravoReCaptcha.reset('enquiry_form');
                    }
                    form.find('.icon-loading').hide();
                }
            })
        })
    </script>
    @endpush