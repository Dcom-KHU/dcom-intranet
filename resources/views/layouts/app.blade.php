<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">

	
    <title>디닷컴</title>
	
	<!-- 사이드바 less -->
   <link rel="stylesheet/less" type="text/css" href="/css/Siderbar.less">

    <!-- 부트 스트랩 -->
    <link href="/css/app.css" rel="stylesheet">
   
   <!-- 레이아웃을 다루는 css -->
    <link href="/css/layout.css" rel="stylesheet">

   <!-- 기본 설정 css -->
    <link href="/css/common.css" rel="stylesheet">
    
   <!-- 부트스트랩 재정의 css -->
   <link href="/css/override.css" rel="stylesheet">

    <!-- csft 토큰 생성 -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
	
	<!-- less -->
   <script src="/js/less.min.js"></script>
   
	<!-- jQuery -->
    <script src="/js/app.js"></script>
   
   <!-- Siderbar JS -->
   <script src="/js/Siderbar.js"></script>
   
	<!-- 토글 -->
	<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
	<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

	<!-- include summernote css/js-->
	<link href="/css/summernote.css" rel="stylesheet">
	<script src="/js/summernote.min.js"></script>
	
	
	<!-- include summernote-ko-KR -->
	<script src="/lang/summernote-ko-KR.js"></script>
	
	<!-- 레이아웃 스크립트 -->
    <script src="/js/layout.js"></script>
	
	<!-- dropzone -->
	<link href="/css/dropzone.min.css" rel="stylesheet">
	<script src="/js/dropzone.js"></script>
	
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="/css/bootstrap-select.min.css">

	<!-- Latest compiled and minified JavaScript -->
	<script src="/js/bootstrap-select.js"></script>

	<!-- (Optional) Latest compiled and minified JavaScript translation files -->
	<script src="/js/defaults-ko_KR.js"></script>
	
	<!-- 달력 -->
	<link href='/css/fullcalendar.css' rel='stylesheet' />
	<link href='/css/fullcalendar.print.css' rel='stylesheet' media='print' />
	<script src='/js/moment.min.js'></script>
	<script src='/js/fullcalendar.min.js'></script>



</head>
<body>

   <div id="wrapper">
      @include('layouts.sidenav')

      <div id="page-content-wrapper">
         <div id="header">
            <button type="button" class="hamburger is-closed animated fadeInLeft" data-toggle="offcanvas">
               <span class="hamb-top"></span>
               <span class="hamb-middle"></span>
               <span class="hamb-bottom"></span>
            </button>
			
				<div id="logo">
					<a href="/"></a>
				</div>
			
         </div>
         <div id="content">
            @yield('content')
         </div>

         
      </div>
   </div>
   
	<div id="footer">
		<div id="address">
			<span>17104 경기도 용인시 기흥구 덕영대로 1732</span>
			<span>전자정보대학 241-4호</span>
		</div>
		<div id="sponsor">
		<!--
			<a href="http://khu.ac.kr/" target="_blank"><img src="https://khlug.org/images/khu.png" alt="" /> 경희대학교</a>
			<a href="https://khlug.org/" target="_blank"><img src="https://khlug.org/images/favicon.gif" alt="" /> KHLUG</a>
			-->
			<a href="http://khu.ac.kr/" target="_blank"><img src="" alt="" /> 경희대학교</a>
			<a href="https://khlug.org/" target="_blank"><img src="" alt="" /> KHLUG</a>
		</div>
	</div>
	
	<script>
		$(document).ready(function() {
			
			/* ajax의 CSRF-TOKEN을 설정해준다. */
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});

			/* summernote 초기화와 관련된 함수 onImageUpload는 콜백 함수이다. */
			$('#summernote').summernote({
				height: 200,
				lang: 'ko-KR',
				focus: true,
				callbacks: {
					onImageUpload: function(files, editor, welEditable) {
						for (var i = files.length - 1; i >= 0; i--) {
							sendFile(files[i], this);
						}
					}
				}
			});

			/* 이미지를 ajax를 통해 업로드하는 함수이다. */
			function sendFile(file, el) {
				var form_data = new FormData();
				form_data.append('file', file);
												
				$.ajax({
					data: form_data,
					type: "POST",
					url: '/uploadimage',
					cache: false,
					contentType: false,
					enctype: 'multipart/form-data',
					processData: false,
					success: function(url) {
						$(el).summernote('editor.insertImage', 'https://dcom.club/download/'+url);
					}
				});
			}
			
			$('#calendar').fullCalendar({
				height: 400,
			    contentHeight: 400,
				events: [
					{
						title: '동계방학',
						start: '2016-12-22'
					},
					{
						title: '2학기 성적 열람 및 공시',
						start: '2016-12-30',
						end: '2017-01-03'
					},
					{
						title: '계절학기',
						start: '2016-12-22',
						end: '2017-01-12'
					},
					{
						title: '종강총회',
						start: '2016-12-21'
					},
					{
						title: '전역!!',
						start: '2018-10-01'
					}
				]
			});
			
		});
		


								
	</script>
</body>
</html>


