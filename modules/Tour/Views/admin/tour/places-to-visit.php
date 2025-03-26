@php
// Initialize default places structure
$defaultPlaces = [
];

// Use existing places or default
$places = $translation->places;
if (!is_array($translation->places)) {
    $places = json_decode(old('places', default: $translation->places), true);
}
if (empty($places)) {
    $places = $defaultPlaces;
    $translation->places = $defaultPlaces;
}
if(isset($places['__place_number__'])){
    unset($places['__place_number__']);
}
@endphp

{{-- Main Container --}}
<div class="form-group-item places-container">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <label class="control-label h5 mb-0">{{__('Places to Visit')}}</label>
        <button type="button" class="btn btn-success btn-sm btn-add-place">
            <i class="icon ion-ios-add-circle-outline"></i>
            {{__('Add New Place')}}
        </button>
    </div>

    {{-- Places List --}}
    <div class="places-wrapper">
        @foreach($places as $place_key => $place)
        <div class="place-item card mb-3">
            {{-- Place Header --}}
            <div class="card-header bg-light">
                <div class="row align-items-center">
                    <div class="col-md-11">
                        <input type="text"
                            name="places[{{$place_key}}][title]"
                            class="form-control"
                            value="{{$place['title'] ?? ''}}"
                            placeholder="{{ $place['title'] ?? __('Enter Place Title')}}">
                    </div>
                    <div class="col-md-1 text-right">
                        <button type="button" class="btn btn-danger btn-sm btn-remove-place">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
            {{-- Place Details --}}
            <div class="card-body">
                <div class="details-wrapper">
                    {{-- Details Header --}}
                    <div class="g-items-header bg-light rounded p-2 mb-3">
                        <div class="row">
                            <div class="col-md-6">{{__("Title")}}</div>
                            <div class="col-md-5">{{__("Image URL")}}</div>
                            <div class="col-md-1">{{__("Actions")}}</div>
                        </div>
                    </div>

                    {{-- Details List --}}
                    <div class="g-items">
                        @if(isset($place['details']) && !empty($place['details']) && is_array($place['details']))
                        @foreach($place['details'] as $detail_key => $detail)
                        <div class="item border-bottom py-2" data-number="{{$detail_key}}">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <input type="text"
                                        name="places[{{$place_key}}][details][{{$detail_key}}][title]"
                                        class="form-control"
                                        value="{{$detail['title'] ?? ''}}"
                                        placeholder="{{__('Enter title')}}">
                                </div>
                                <div class="col-md-5">
                                    <input type="text"
                                        name="places[{{$place_key}}][details][{{$detail_key}}][image_url]"
                                        class="form-control"
                                        value="{{$detail['image_url'] ?? ''}}"
                                        placeholder="{{__('Enter image URL')}}">
                                </div>
                                <div class="col-md-1 text-center">
                                    <button type="button" class="btn btn-danger btn-sm btn-remove-detail">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        @endif
                    </div>

                    {{-- Add Detail Button --}}
                    <div class="text-right mt-3">
                        <button type="button" class="btn btn-info btn-sm btn-add-detail">
                            <i class="icon ion-ios-add-circle-outline"></i>
                            {{__('Add Detail')}}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

{{-- Templates --}}
<div class="d-none">
    {{-- Place Template --}}
    <div class="place-template">
        <div class="place-item card mb-3">
            <div class="card-header bg-light">
                <div class="row align-items-center">
                    <div class="col-md-11">
                        <input type="text" name="places[__place_number__][title]" class="form-control" placeholder="{{__('Enter Place Title')}}">
                    </div>
                    <div class="col-md-1 text-right">
                        <button type="button" class="btn btn-danger btn-sm btn-remove-place">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="details-wrapper">
                    <div class="g-items-header bg-light rounded p-2 mb-3">
                        <div class="row">
                            <div class="col-md-6">{{__("Title")}}</div>
                            <div class="col-md-5">{{__("Image URL")}}</div>
                            <div class="col-md-1">{{__("Actions")}}</div>
                        </div>
                    </div>
                    <div class="g-items"></div>
                    <div class="text-right mt-3">
                        <button type="button" class="btn btn-info btn-sm btn-add-detail">
                            <i class="icon ion-ios-add-circle-outline"></i>
                            {{__('Add Detail')}}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Detail Template --}}
    <div class="detail-template">
        <div class="item border-bottom py-2" data-number="__detail_number__">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <input type="text" name="places[__place_number__][details][__detail_number__][title]" class="form-control" placeholder="{{__('Enter title')}}">
                </div>
                <div class="col-md-5">
                    <input type="text" name="places[__place_number__][details][__detail_number__][image_url]" class="form-control" placeholder="{{__('Enter image URL')}}">
                </div>
                <div class="col-md-1 text-right">
                    <button type="button" class="btn btn-danger btn-sm btn-remove-detail">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('js')
<script>
    (function($) {
        'use strict';

        class PlaceDetailManager {
            constructor() {
                this.placesWrapper = $('.places-wrapper');
                this.placeTemplate = $('.place-template').html();
                this.detailTemplate = $('.detail-template').html();
                this.initializeEventListeners();
            }

            initializeEventListeners() {
                $(document)
                    .on('click', '.btn-add-place', this.handleAddPlace.bind(this))
                    .on('click', '.btn-add-detail', this.handleAddDetail.bind(this))
                    .on('click', '.btn-remove-place', this.handleRemovePlace.bind(this))
                    .on('click', '.btn-remove-detail', this.handleRemoveDetail.bind(this));
            }

            handleAddPlace() {
                const placeCount = this.placesWrapper.find('.place-item').length;
                const template = this.placeTemplate.replace(/__place_number__/g, placeCount);
                this.placesWrapper.append(template);
                return false; // Prevent default form submission
            }

            handleAddDetail(e) {
                const placeItem = $(e.currentTarget).closest('.place-item');
                const placeIndex = this.placesWrapper.find('.place-item').index(placeItem);
                const detailsContainer = placeItem.find('.g-items');
                const detailCount = detailsContainer.find('.item').length;

                const template = this.detailTemplate
                    .replace(/__place_number__/g, placeIndex)
                    .replace(/__detail_number__/g, detailCount);

                detailsContainer.append(template);
                return false; // Prevent default form submission
            }

            handleRemovePlace(e) {
                if (confirm('Are you sure you want to remove this place?')) {
                    $(e.currentTarget).closest('.place-item').remove();
                    this.reindexPlaces();
                }
                return false; // Prevent default form submission
            }

            handleRemoveDetail(e) {
                if (confirm('Are you sure you want to remove this detail?')) {
                    const detailElement = $(e.currentTarget).closest('.item');
                    const placeItem = detailElement.closest('.place-item');
                    detailElement.remove();
                    this.reindexDetails(placeItem);
                }
                return false; // Prevent default form submission
            }

            reindexPlaces() {
                this.placesWrapper.find('.place-item').each((placeIndex, element) => {
                    $(element).find('[name^="places["]').each((_, input) => {
                        const name = $(input).attr('name');
                        const newName = name.replace(/places\[\d+\]/, `places[${placeIndex}]`);
                        $(input).attr('name', newName);
                    });
                    this.reindexDetails($(element));
                });
            }

            reindexDetails(placeItem) {
                const placeIndex = this.placesWrapper.find('.place-item').index(placeItem);
                placeItem.find('.g-items .item').each((detailIndex, element) => {
                    $(element).attr('data-number', detailIndex);
                    $(element).find('[name^="places["]').each((_, input) => {
                        const name = $(input).attr('name');
                        const newName = name.replace(
                            /places\[\d+\]\[details\]\[\d+\]/,
                            `places[${placeIndex}][details][${detailIndex}]`
                        );
                        $(input).attr('name', newName);
                    });
                });
            }
        }

        // Initialize on document ready
        $(document).ready(function() {
            new PlaceDetailManager();
        });
    })(jQuery);
</script>
@endpush