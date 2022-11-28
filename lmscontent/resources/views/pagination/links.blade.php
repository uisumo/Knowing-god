@if ($paginator->hasPages())
    <nav class="navigation pagination" role="navigation">
		<div class="nav-links">
			{{-- Previous Page Link --}}
			@if ($paginator->onFirstPage())
				<li class="disabled"><span>&laquo;</span></li>
			@else
				<li><a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="prev page-numbers">&laquo;</a></li>
			@endif

			{{-- Pagination Elements --}}
			@foreach ($elements as $element)
				{{-- "Three Dots" Separator --}}
				@if (is_string($element))
					<li class="disabled"><span>{{ $element }}</span></li>
				@endif

				{{-- Array Of Links --}}
				@if (is_array($element))
					@foreach ($element as $page => $url)
						@if ($page == $paginator->currentPage())
							<li class="active current"><span>{{ $page }}</span></li>
						@else
							<li><a href="{{ $url }}" class="page-numbers">{{ $page }}</a></li>
						@endif
					@endforeach
				@endif
			@endforeach

			{{-- Next Page Link --}}
			@if ($paginator->hasMorePages())
				<li><a href="{{ $paginator->nextPageUrl() }}" rel="next" class="next page-numbers">&raquo;</a></li>
			@else
				<li class="disabled"><span>&raquo;</span></li>
			@endif
		</div>
	</nav>
@endif
