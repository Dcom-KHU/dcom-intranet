@section('layout-sub')
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<meta property="og:image" content="/image/og_image.jpg">
	<meta property="og:title" content="디닷컴 인트라넷">
	<meta name="csrf-token" content="{{ csrf_token() }}">

    <title>디닷컴</title>

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
	<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/gh/orioncactus/pretendard/dist/web/static/pretendard-dynamic-subset.css" />

	<link href="/css/new/common.css" rel="stylesheet">

	<!-- include summernote css/js-->
	<link href="/css/summernote-lite.css" rel="stylesheet">
	<script src="/js/summernote-lite.min.js"></script>
	
	
	<!-- include summernote-ko-KR -->
	<script src="/lang/summernote-ko-KR.js"></script>
	
	<!-- 레이아웃 스크립트 -->
    <script src="/js/layout.js"></script>
	
	<!-- dropzone -->
	<link href="/css/dropzone.min.css" rel="stylesheet">
	<script src="/js/dropzone.js"></script>
	
	<!-- Latest compiled and minified JavaScript -->
	<script src="/js/bootstrap-select.js"></script>

	<!-- (Optional) Latest compiled and minified JavaScript translation files -->
	<script src="/js/defaults-ko_KR.js"></script>
	
	<!-- 달력 -->
	<script src='/js/moment.min.js'></script>
	<script src='/js/fullcalendar.min.js'></script>
	<script>
		$(document).ready(function() {
			
			/* ajax의 CSRF-TOKEN을 설정해준다. */
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});

			/* summernote 초기화와 관련된 함수 onImageUpload는 콜백 함수이다. */
			$('.summernote').summernote({
				height: 200,
				lang: 'ko-KR',
				toolbar: [
					['style', ['style']],
					['font', ['bold', 'underline', 'clear']],
					['color', ['color']],
					['para', ['ul', 'ol', 'paragraph']],
					['table', ['table']],
					['insert', ['link', 'picture', 'video']],
					['view', ['fullscreen', 'codeview', 'help']]
        		],
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
						$(el).summernote('editor.insertImage', '/download/'+url);
					}
				});
			}

			$(".dropdown-toggle").click(function() {
				$(this).find(".dropdown-menu").toggle();
			})
			
		});
		


								
	</script>
@show