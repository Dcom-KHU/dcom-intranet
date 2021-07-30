<!DOCTYPE html>
<html lang="en">
<head>
	@include('layouts.layout-sub')
	<link rel="stylesheet" href="/css/new/before-login.css">
	<script>
	function validate(f) {
		if(!f.userid.value || !f.email.value || !f.password.value || !f.password_confirmation.value || !f.realname.value || !f.admissionyear.value) {
			alert("빠짐 없이 입력해주세요.");
			return false;
		}

		if(!/^\w+@\w+(\.\w+)+$/.test(f.email.value)) {
			alert("이메일 형식을 확인해주세요.");
			return false;
		}

		if(f.password.value != f.password_confirmation.value) {
			alert("비밀번호를 확인해주세요.");
			return false;
		}

		if(f.userid.value.length > 255) {
			alert("아이디는 255자까지만 입력 가능합니다.");
			return false;
		}

		if(f.email.value.length > 255) {
			alert("이메일은 255자까지만 입력 가능합니다.");
			return false;
		}

		if(f.password.value.length < 6) {
			alert("비밀번호는 6자리 이상 가능합니다.");
			return false;
		}

		if(f.realname.value.length > 255) {
			alert("이름은 255자까지만 입력 가능합니다.");
			return false;
		}

		if(f.phone.value.length > 255) {
			alert("전화번호는 255자까지만 입력 가능합니다.");
			return false;
		}

		if(!/^[0-9]{2}$/.test(f.admissionyear.value)) {
			alert("입학년도는 YY 형태로 맞춰주세요.");
			return false;
		}

		return true;
	}

    $(function() {
        $("#userid").on("blur", function() {
            $.getJSON("/api/user/id/" + $(this).val(), function(res) {
                if(res.valid) {
                    $("#userid ~ .help-block").remove();
                } else {
                    if($("#userid ~ .help-block").length === 0)
                        $("#userid").parent().append("<div class='help-block'></div>");
                    $("#userid ~ .help-block").text("중복된 아이디이거나 유효하지 않습니다. (아이디 정규식 : /^[\\w-]*$/)");
                }
            })
        });

        $("#email").on("blur", function() {
            $.getJSON("/api/user/email/" + $(this).val(), function(res) {
                if(res.valid) {
                    $("#email ~ .help-block").remove();
                } else {
                    if($("#email ~ .help-block").length === 0)
                        $("#email").parent().append("<div class='help-block'></div>");
                    $("#email ~ .help-block").text("중복된 이메일이거나 이메일 형식에 맞지 않습니다.");
                }
            })
        });

        $("#password, #password-confirm").on("blur", function() {
            let error = false;
            
            if($("#password").val().length < 6) {
                error = "비밀번호는 6자리 이상 가능합니다.";
            } else if($("#password").val() != $("#password-confirm").val()) {
                error = "비밀번호가 다릅니다.";
            }

            if(!error) {
                $("#password ~ .help-block").remove();
            } else {
                if($("#password ~ .help-block").length === 0)
                    $("#password").parent().append("<div class='help-block'></div>");
                $("#password ~ .help-block").text(error);
            }
        });

        $("#admissionyear").on("blur", function() {
            if(/^[0-9]{2}$/.test($("#admissionyear").val())) {
                $("#admissionyear ~ .help-block").remove();
            } else {
                if($("#admissionyear ~ .help-block").length === 0)
                    $("#admissionyear").parent().append("<div class='help-block'></div>");
                $("#admissionyear ~ .help-block").text("입학년도는 YY 형태로 맞춰주세요.");
            }
        });
    })
	</script>
</head>
<body>
	<div class="container">
		<div class="panel panel-default">
			<div class="form-main">
				<div class="panel-heading">회원가입</div>
				<div class="panel-body">
					<form class="form-horizontal" role="form" method="POST" action="{{ url('/register') }}" onsubmit="return validate(this);">
						{{ csrf_field() }}

						<div class="form-group{{ $errors->has('userid') ? ' has-error' : '' }}">
							<div class="col-md-12">
								<input id="userid" type="text" class="form-control" name="userid" value="{{ old('userid') }}" required autofocus placeholder="아이디">
								@if ($errors->has('userid'))
									<div class="help-block">
										{{ $errors->first('userid') }}
									</div>
								@endif
							</div>
						</div>
						
						<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
							<div class="col-md-12">
								<input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required placeholder="이메일">
								@if ($errors->has('email'))
									<div class="help-block">
										{{ $errors->first('email') }}
									</div>
								@endif
							</div>
						</div>
						
						<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
							<div class="col-md-12">
								<input id="password" type="password" class="form-control" name="password" required placeholder="비밀번호">
								@if ($errors->has('password'))
									<div class="help-block">
										{{ $errors->first('password') }}
									</div>
								@endif
							</div>
						</div>

						<div class="form-group">		
							<div class="col-md-12">
								<input id="password-confirm" type="password" class="form-control" name="password_confirmation" required placeholder="비밀번호 확인">
							</div>
						</div>
						
						<div class="form-group{{ $errors->has('realname') ? ' has-error' : '' }}">
							<div class="col-md-12">
								<input id="realname" type="text" class="form-control" name="realname" value="{{ old('realname') }}" required placeholder="이름">
								@if ($errors->has('realname'))
									<div class="help-block">
										{{ $errors->first('realname') }}
									</div>
								@endif
							</div>
						</div>
						
						<div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
							<div class="col-md-12">
								<input id="phone" type="text" class="form-control" name="phone" value="{{ old('phone') }}" placeholder="전화번호">
								@if ($errors->has('phone'))
									<div class="help-block">
										{{ $errors->first('phone') }}
									</div>
								@endif
							</div>
						</div>

						<div class="form-group{{ $errors->has('admissionyear') ? ' has-error' : '' }}">
							<div class="col-md-12">
								<input id="admissionyear" type="number" class="form-control" name="admissionyear" value="{{ old('admissionyear') }}" required placeholder="입학년도 (ex 22)">
								@if ($errors->has('admissionyear'))
									<div class="help-block">
										{{ $errors->first('admissionyear') }}
									</div>
								@endif
							</div>
						</div>
										
						<div class="form-group">
							<div class="col-md-12">
								<button type="submit" class="btn btn-success">회원가입</button>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-12">
								<a class="btn btn-cancel" href="{{ url('/') }}">취소</a>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</body>
</html>