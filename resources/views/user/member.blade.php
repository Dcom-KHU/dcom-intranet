@extends('layouts.app')

@section('content')

<div class="col-md-8 col-md-offset-2">
	<div class="panel panel-default">
		<div class="panel-heading">
			회원정보
		</div>
		<div class="panel-body">
			<?php $users=App\User::where('confirm', 1)->latest('logintime')->get()?>
			@foreach($users as $user)
				<div class="panel panel-success group-list">
					<div class="panel-heading">
						<span>
							<span id="numberbox">{{$user->admissionyear}}</span>
							<span id="name">{{$user->realname}}</span>
						</span>
						<span style="float:right;">
							최근 접속 : {{$user->logintime}}
						</span>
					</div>
					<div class="panel-body">
						<i class="fa fa-envelope" aria-hidden="true"></i>&nbsp;&nbsp;{{$user->email}}
						<span style="float:right;">
							<i class="fa fa-phone" aria-hidden="true"></i>&nbsp;&nbsp;{{$user->phone}}
						</span>
					</div>
				</div>
			@endforeach
		</div>
	</div>
</div>
@endsection
