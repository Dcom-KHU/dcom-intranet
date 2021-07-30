/* board에 관련된 자바스크립트 */

var postForm = function(loc) {
	// loc은 textarea의 위치를 찾는 변수이다.
	var content = $('.'+loc+' textarea[name="content"]').summernote('code'),
		filteredContent = $(content).text().replace(/\s+/g, '');
	if(filteredContent){
		// 없어도 되는 코드이지만 textarea를 form-submit으로 보낼 경우 빠르게 적용이 안되는 에러를 수정하기 위해
		$('textarea[name="content"]').val(content);
		return true;
	}else{
		alert('내용을 입력해 주세요.');
		return false;
	}
}

function modify(commentid){
	$('.reply').html(''); // 현재 열려있는 댓글 창들을 닫는다.
	
	if($('.modify #target').html()){
		if(confirm('수정중인 글을 취소하시겠습니까?')){
			location.reload();
		}
		return;
	}
	
	// standard change
	$('.main').removeAttr("id");
	$('.comment'+commentid).attr("id", "standard");
	
	// summernote+commentid는 임시 저장소 이다.
	$('.comment'+commentid).html('<div id="target"><textarea id="summernote'+commentid+'" name="content">'+$('.comment'+commentid).html()+'</textarea><div class="dropzone" id="modifydropzone"></div><div class="col-md-12"><button type="submit" class="btn btn-success" style="float:right;">기록 수정</button><button class="btn btn-success" style="float:right; margin-right:10px;" onclick="return cancelmodify()">수정 취소</button></div></div>');
	
	new Dropzone("div#modifydropzone", {
		url: '/uploadfile',
		params: {
			_token: $('meta[name="csrf-token"]').attr('content'),
		},
		success: function(file, response) {
			var link = '<a href="http://dcomstudy.com/download/'+response+'">'+file.name+'</a></br>';
			$('#standard textarea[name="content"]').summernote('pasteHTML', link);
		}
	});
	
	/* summernote 초기화와 관련된 함수 onImageUpload는 콜백 함수이다. */
	$('#summernote'+commentid).summernote({
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
				$(el).summernote('editor.insertImage', 'http://dcomstudy.com/download/'+url);
			}
		});
	}
}

function cancelmodify(){
	if(confirm('수정을 취소하시겠습니까?')){
		location.reload();
		return true;
	}else
		return false;
}

function remove(commentid){
	if(confirm('해당 댓글을 삭제하시겠습니까?')){
		$.ajax({
			data: {
				'commentid' : commentid,
			},
			type: "POST",
			url: '/comment/'+commentid+'/remove',
			cache: false,
			contentType: false,
			processData: false,
			success: function(data) {
				if(data == 'true') location.reload();
				else alert('자신이 작성한 댓글만 삭제할 수 있습니다.');
			}
		});
		return true;
	}else
		return false;
}

function remove2(boardid, id){
	if(confirm('해당 글을 삭제하시겠습니까?')){
		$.ajax({
			data: {
				'boardid' : boardid,
				'id' : id,
			},
			type: "POST",
			url: '/'+boardid+'/'+id+'/remove',
			cache: false,
			contentType: false,
			processData: false,
			success: function(data) {
				if(data == 'true') $(location).attr('href', '/'+boardid);
				else alert('자신이 작성한 글만 삭제할 수 있습니다.');
			}
		});
		return true;
	}else
		return false;
}


function reply(commentid){
	
				
	if($('.reply'+commentid).html() != ''){
		$('.reply'+commentid).html('');
		return;
	}
	
	$('.reply').html('');
	$('.modify #target').html('');
	
	
	// standard change
	$('.main').removeAttr("id");
	$('.reply'+commentid).attr("id", "standard");

	
	// summernote+commentid는 임시 저장소 이다.
	$('.reply'+commentid).html('<div class="panel panel-default" style="margin-left:15px; margin-top:10px;"><div class="panel-heading">답글 작성</div><div class="panel-body"><textarea id="summernote_'+commentid+'" name="content"></textarea><div class="dropzone" id="replydropzone"></div><div class="col-md-12"><button type="submit" class="btn btn-success" style="float:right;">답글 작성</button></div></div></div>');
	new Dropzone("div#replydropzone", {
		url: '/uploadfile',
		params: {
			_token: $('meta[name="csrf-token"]').attr('content'),
		},
		success: function(file, response) {
			var link = '<a href="http://dcomstudy.com/download/'+response+'">'+file.name+'</a></br>';
			$('#standard textarea[name="content"]').summernote('pasteHTML', link);
		}
	});
	
	/* summernote 초기화와 관련된 함수 onImageUpload는 콜백 함수이다. */
	$('#summernote_'+commentid).summernote({
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
				$(el).summernote('editor.insertImage', 'http://dcomstudy.com/download/'+url);
			}
		});
	}
}


/* 위 함수와 기능은 같지만, 익명 기능이 추가된 함수이다. */

function modify2(commentid, anonymous){
	
	$('.reply').html('');
	if($('.modify #target').html()){
		if(confirm('수정중인 글을 취소하시겠습니까?')){
			location.reload();
		}
		return;
	}
	
	// standard change
	$('.main').removeAttr("id");
	$('.comment'+commentid).attr("id", "standard");
	
	// summernote+commentid는 임시 저장소 이다.
		
	var ischeck = "checked";
	if(anonymous==1) ischeck = "";

	$('.comment'+commentid).html('<div id="target"><textarea id="summernote'+commentid+'" name="content">'+$('.comment'+commentid).html()+'</textarea><div class="dropzone" id="modifydropzone"></div><div class="col-md-12"><span id="toggle"><input type="checkbox" name="toggle" id="modifyanonymous'+commentid + '" data-onstyle="success" data-offstyle="danger" '+ ischeck +' ><input type="hidden" name="anonymous" value="0"></span><button type="submit" class="btn btn-success" style="float:right;">기록 수정</button><button class="btn btn-success" style="float:right; margin-right:10px;" onclick="return cancelmodify()">수정 취소</button></div></div>');
	
	$(function() {
		$('#modifyanonymous' +commentid).bootstrapToggle({
			on: '실명',
			off: '익명'
		})

		$( "#modifyanonymous" +commentid).change(function() {
			if($('#modifyanonymous' +commentid).is(":checked")) {
				$('input[name=anonymous]').val('0');
			}else{
				$('input[name=anonymous]').val('1');
			}
		});
	})
	
	new Dropzone("div#modifydropzone", {
		url: '/uploadfile',
		params: {
			_token: $('meta[name="csrf-token"]').attr('content'),
		},
		success: function(file, response) {
			var link = '<a href="http://dcomstudy.com/download/'+response+'">'+file.name+'</a></br>';
			$('#standard textarea[name="content"]').summernote('pasteHTML', link);
		}
	});
	
	/* summernote 초기화와 관련된 함수 onImageUpload는 콜백 함수이다. */
	$('#summernote'+commentid).summernote({
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
				$(el).summernote('editor.insertImage', 'http://dcomstudy.com/download/'+url);
			}
		});
	}
}


function reply2(commentid){
	$('.reply').html('');
	$('.modify #target').html('');
	
	
	// standard change
	$('.main').removeAttr("id");
	$('.reply'+commentid).attr("id", "standard");
			
	if($('.reply'+commentid).html() != ''){
		$('.reply'+commentid).html('');
		return;
	}
	
	// summernote+commentid는 임시 저장소 이다.
	$('.reply'+commentid).html('<div class="panel panel-default" style="margin-left:15px; margin-top:10px;"><div class="panel-heading">답글 작성</div><div class="panel-body"><textarea id="summernote_'+commentid+'" name="content"></textarea><div class="dropzone" id="replydropzone"></div><div class="col-md-12"><span id="toggle"><input type="checkbox" checked name="toggle" id="replyanonymous" data-onstyle="success" data-offstyle="danger"><input type="hidden" name="anonymous" value="0"></span><button type="submit" class="btn btn-success" style="float:right;">답글 작성</button></div></div></div>');
	
	$(function() {
		$('#replyanonymous').bootstrapToggle({
			on: '실명',
			off: '익명'
		})

		$( "#replyanonymous" ).change(function() {
			if($('#replyanonymous').is(":checked")) {
				$('input[name=anonymous]').val('0');
			}else{
				$('input[name=anonymous]').val('1');
			}
		});
	})
	
	new Dropzone("div#replydropzone", {
		url: '/uploadfile',
		params: {
			_token: $('meta[name="csrf-token"]').attr('content'),
		},
		success: function(file, response) {
			var link = '<a href="http://dcomstudy.com/download/'+response+'">'+file.name+'</a></br>';
			$('#standard textarea[name="content"]').summernote('pasteHTML', link);
		}
	});
	
	/* summernote 초기화와 관련된 함수 onImageUpload는 콜백 함수이다. */
	$('#summernote_'+commentid).summernote({
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
				$(el).summernote('editor.insertImage', 'http://dcomstudy.com/download/'+url);
			}
		});
	}
}

/* 검색 함수 */
function search(boardid){
	var mode = $('.selectpicker').val();
	var text = $('#searchtext').val();

	if(mode == '제목'){
		$(location).attr('href', '/'+boardid+'/search/title/'+text);
	}else if(mode == '내용'){
		$(location).attr('href', '/'+boardid+'/search/content/'+text);
	}else if(mode == '제목+내용'){
		$(location).attr('href', '/'+boardid+'/search/both/'+text);
	}else{
		return false;
	}
}

function groupsearch(boardid){
	var mode = $('.selectpicker').val();
	var text = $('#searchtext').val();
	
	if(mode == '제목'){
		$(location).attr('href', '/group/'+boardid+'/search/title/'+text);
	}else if(mode == '내용'){
		$(location).attr('href', '/group/'+boardid+'/search/content/'+text);
	}else if(mode == '제목+내용'){
		$(location).attr('href', '/group/'+boardid+'/search/both/'+text);
	}else{
		return false;
	}
}

function removegroup(type, boardid){
	if(confirm('해당 글을 삭제하시겠습니까?')){
		$.ajax({
			data: {
				'type' : type,
				'boardid' : boardid,
			},
			type: "POST",
			url: '/group/'+type+'/'+boardid+'/remove',
			cache: false,
			contentType: false,
			processData: false,
			success: function(data) {
				if(data == 'true') $(location).attr('href', '/group/'+type);
				else alert('자신이 작성한 글만 삭제할 수 있습니다.');
			}
		});
		return true;
	}else
		return false;
}