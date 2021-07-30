@extends('layouts.app')

@section('content')

<div class="col-md-8 col-md-offset-2">
	<div class="panel panel-default">
		<div class="panel-heading">
			회원정보
		</div>
		<div class="panel-body">

			<div class="panel panel-success">
				<div class="panel-heading">
					아이디
				</div>
				<div class="panel-body">
					{{Auth::user()->userid}}
				</div>
			</div>
		
			<div class="panel panel-success">
				<div class="panel-heading">
					이메일
				</div>
				<div class="panel-body">
					{{Auth::user()->email}}
				</div>
			</div>
			
			<div class="panel panel-success">
				<div class="panel-heading">
					이름
				</div>
				<div class="panel-body">
					{{Auth::user()->realname}}
				</div>
			</div>
			
			<div class="panel panel-success">
				<div class="panel-heading">
					전화번호
				</div>
				<div class="panel-body">
					{{Auth::user()->phone}}
				</div>
			</div>
			
			<div class="panel panel-success">
				<div class="panel-heading">
					입학년도
				</div>
				<div class="panel-body">
					{{Auth::user()->admissionyear}}
				</div>
			</div>
			

			
			<div class="form-group">
				<div class="col-md-12">
					<button onclick="location.href='/user/modify'" class="btn btn-success">정보수정</button>
				</div>
			</div>

		</div>
	</div>
</div>
@endsection
