@extends('layouts.app')

@section('content')

<div class="col-md-8 col-md-offset-2">
	<div class="panel panel-default">
		<div class="panel-heading">승락 대기중인 회원</div>
		<div class="panel-body">
			
			<ul class="list-group">
				@foreach ($users as $user)
					<li class="list-group-item" style="height:50px;">
						<span style="line-height:33px;">
							<span id="numberbox">{{$user->admissionyear}}</span>
							<span id="name">{{$user->realname}}</span>
						</span>
						<span style="float:right;">
							<form action="/auth/{{$user->userid}}" method="POST" onsubmit="return confirm('해당 회원을 승락합니다.')">
								{{ csrf_field() }}
								<button type="submit"  class="btn btn-success">
									가입확인
								</button>
							</form>
						</span>
					</li>
				@endforeach
			</ul>
		</div>
	</div>
</div>
@endsection
