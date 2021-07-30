@extends('layouts.layout')

@section('content')

<style>
.user-list {
	margin:-20px 0;
}
.user-list li {
	padding:20px 0 15px 0;
	border-bottom:1px solid #eee;
	word-break:break-all;
}
.user-list li:last-child {
	border-bottom:0;
}
.user-list .panel-heading, .user-list .panel-body {
	display:flex;
	justify-content:space-between;
	align-items:center;
}
.user-list .panel-heading > div, .user-list .panel-body > div {
	margin-bottom:5px;
}
.user-list .latest {
	color:#aaa;
	font-size:0.9em;
	font-weight:100;
}
.user-list .numberbox {
	margin:0;
}
.user-list .user-name {
	font-size:1.2em;
}
.user-list .buttons {
	text-align:right;
}
.user-list .buttons form {
	display:inline-block;
}
.user-list .buttons .btn {
	padding:6px 12px;
}
@media (max-width: 799px) {
	.user-list .panel-heading, .user-list .panel-body {
		display:block;
	}
}
</style>
<script>
function confirm_auth_delete() {
	return confirm("정말 승인을 해제하시겠습니까?\n\n해제시 해당 회원은 인트라넷 이용이 불가능해집니다.");
}
function confirm_admin() {
	return confirm("정말 관리자로 지정하시겠습니까?\n\n지정시 해당 회원은 인트라넷 내의 모든 권한을 부여받습니다.");
}
function confirm_admin_delete() {
	return confirm("정말 관리자 지정을 해제하시겠습니까?\n\n해제시 해당 회원은 인트라넷 관리자 권한이 박탈됩니다.");
}
</script>
<div class="col-md-8 col-md-offset-2">
	<div class="card-section ">
		<div class="card-header">
			회원정보
		</div>
		<div class="card-content">
			<ul class="user-list">
				@foreach($users as $user)
					<li>
						<div class="panel-heading">
							<div class="user-name">
								<span class="numberbox">{{$user->admissionyear}}</span>
								<span class="name">{{$user->realname}}</span>
							</div>
							<div class="latest">
								최근 접속 : {{$user->logintime}}
							</div>
						</div>
						<div class="panel-body">
							<div>
								<i class="fa fa-envelope" aria-hidden="true"></i>&nbsp;&nbsp;{{$user->email}}
							</div>
							<div class="buttons">
							@if($user->id != Auth::user()->id)
								@if($user->admin === 1)
									<form action="/user/admin/delete/{{$user->userid}}" method="post" onsubmit="return confirm_admin_delete();">
										{{ csrf_field() }}
										<button type="submit" class="btn btn-danger">
											관리자 해제
										</button>
									</form>
								@else
									<form action="/auth/delete/{{$user->userid}}" method="post" onsubmit="return confirm_auth_delete();">
										{{ csrf_field() }}
										<button type="submit" class="btn btn-danger">
											승인 해제
										</button>
									</form>
									<form action="/user/admin/{{$user->userid}}" method="post" onsubmit="return confirm_admin();">
										{{ csrf_field() }}
										<button type="submit" class="btn btn-primary">
											관리자 지정
										</button>
									</form>
								@endif
							@endif
							</div>
						</div>
					</li>
				@endforeach
			</ul>
		</div>
	</div>
</div>
@endsection
