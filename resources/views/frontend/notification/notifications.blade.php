@extends('frontend.master')
@section('content')
@section('styles')
@endsection

<div class="container h-100 documentation">
		<div class="row">
			<div class="accordion" id="accordionExample">
				@foreach ($notifications as $key=>$item)
					@if(isset($item) && $item != null)
						<div class="accordion-item">
							<h2 class="accordion-header">
								<button class="accordion-button collapsed text-success custom-outline-none custom-box-shadow-none" type="button" data-bs-toggle="collapse"
									data-bs-target="{{ '#collapse'.$key }}" aria-expanded="false" aria-controls="{{ 'collapse'.$key }}">
									{{$item->notification_title}}
								</button>
							</h2>
							<div id="{{ 'collapse'.$key }}" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
								<div class="accordion-body">
									<p>
										{!! $item->notification_message !!}
									</p>
								</div>
							</div>
						</div>
					@endif
				@endforeach
			</div>
		</div>
</div> 

@endsection