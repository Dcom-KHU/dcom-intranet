@extends('layouts.layout')

@section('content')
<style>
.auth-list li .btn {
	border:0;
	border-radius:5px;
	padding:8px 16px;
	color:#fff;
	cursor:pointer;
}
.auth-list li form {
	display:inline-block;
}
.auth-list li .numberbox {
	margin:0;
}
.auth-list li .btn-success {
	background-color:#4e73df;
}
.auth-list li .btn-success:hover {
	background-color:#4363c2;
}
.auth-list li .btn-danger {
	background-color:#d13f4a;
}
.auth-list li .btn-danger:hover {
	background-color:#b1353f;
}
.auth-list li .userid {
	color:#aaa;
}
.card-section .card-content ul.content-list li .list-long {
	text-overflow:ellipsis;
	white-space:nowrap;
	padding-right:5px;
}
@media (max-width: 799px) {
	.card-section .card-content ul.content-list li .list-long {
		margin-bottom:0;
	}
	.card-section .card-content ul.content-list li {
		display:flex;
		padding:5px;
	}
}
</style>

	<div class="card-section half-card">
		<div class="card-header">승인 대기중인 회원</div>
		<div class="card-content">
			
			<ul class="list-group auth-list content-list">
				@foreach ($users as $user)
					<li class="list-group-item">
						<div class="list-long">
							<span class="numberbox">{{$user->admissionyear}}</span>
							<span class="name">{{$user->realname}}</span>
							<span class="userid">({{$user->userid}})</span>
						</div>
						<div>
							<form action="/auth/{{$user->userid}}" method="POST" onsubmit="return confirm('해당 회원을 승인합니다.')">
								{{ csrf_field() }}
								<button type="submit" class="btn btn-primary">
									승인
								</button>
							</form>
							<form action="/user/delete/{{$user->userid}}" method="POST" onsubmit="return confirm('해당 회원을 거절합니다.\n\n해당 유저의 모든 정보가 삭제됩니다.')">
								{{ csrf_field() }}
								<button type="submit" class="btn btn-danger">
									거절
								</button>
							</form>
						</div>
					</li>
				@endforeach
				@if(count($users) == 0)
					승인 대기중인 회원이 없습니다.
				@endif
			</ul>
		</div>
	</div>
@endsection
