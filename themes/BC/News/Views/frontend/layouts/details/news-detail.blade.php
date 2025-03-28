<div class="article">
    @if($row->gallery)
        @include('Layout::global.details.gallery')
    @else
        <div class="header">
            @if($image_url = get_file_url($row->image_id, 'full'))
                <header class="post-header">
                    <img src="{{ $image_url  }}" alt="{{$translation->title}}">
                </header>
                <div class="cate">
                    @php $category = $row->category; @endphp
                    @if(!empty($category))
                        @php $t = $category->translate(); @endphp
                        <ul>
                            <li>
                                <a href="{{$category->getDetailUrl(app()->getLocale())}}">
                                    {{$t->name ?? ''}}
                                </a>
                            </li>
                        </ul>
                    @endif
                </div>
            @endif
        </div>
    @endif
    <h2 class="title">{{$translation->title}}</h2>
    <div class="post-info">
        <ul>
            @if(!empty($row->author))
                <li>
                    <span> {{ __('BY ')}} </span>
                    {{$row->author->getDisplayName() ?? ''}}
                </li>
            @endif
            <li> {{__('DATE ')}}  {{ display_date($row->updated_at)}}  </li>
        </ul>
    </div>
    <div class="post-content"> {!! $translation->content !!}</div>
    <div class="space-between">
        @if (!empty($tags = $row->getTags()) and count($tags) > 0)
            <div class="tags">
                {{__("Tags:")}}
                @foreach($tags as $tag)
                    @php $t = $tag->translate(); @endphp
                    <a href="{{ $tag->getDetailUrl(app()->getLocale()) }}" class="tag-item"> {{$t->name ?? ''}} </a>
                @endforeach
            </div>
        @endif
        <div class="share"> {{__("Share")}}
            <a class="facebook share-item" href="https://www.facebook.com/sharer/sharer.php?u={{$row->getDetailUrl()}}&amp;title={{$translation->title}}" target="_blank" original-title="{{__("Facebook")}}"><i class="fa fa-facebook fa-lg"></i></a>
            <a class="twitter share-item" href="https://twitter.com/share?url={{$row->getDetailUrl()}}&amp;title={{$translation->title}}" target="_blank" original-title="{{__("Twitter")}}"><i class="fa fa-twitter fa-lg"></i></a>
        </div>
    </div>
</div>

