@extends('layouts.app')

@section('content')
<div class="col-md-8 col-md-offset-2">
			
	@foreach ($groups->reverse() as $study)
		<a href="/group/study/{{$study->id}}" style="text-decoration: none;">
			<div class="panel panel-success group-list" style="height:146px;">
				<div class="panel-heading">
					{{$study->title}}
					<?php $commentcount = App\Comment::where('boardid', $study->id)->get()->count()?>
					&nbsp;&nbsp;<i class="fa fa-pencil" aria-hidden="true"></i>({{$commentcount}})
					<span style="float:right;">
						<i class="fa fa-user" aria-hidden="true"></i>
						<?php $user = App\User::where('userid', $study->userid)->first() ?>
						<span id="numberbox">{{$user->admissionyear}}</span>{{$user->realname}}
					</span>
				</div>
				<div class="panel-body" style="color:#333;">
					{{$study->content}}
					<div style="white-space:nowrap; padding-top:30px; overflow-x:scroll; margin:0 -5px;">
						<?php $users = App\GroupsUsers::where('groupid', $study->id)->get() ?>
						@foreach ($users as $user)
							<span style="padding:0 5px;">
								<?php $id = App\User::where('userid', $user->userid)->first() ?>
								<span id="numberbox">{{$id->admissionyear}}</span>{{$id->realname}}
							</span>
						@endforeach
					</div>
				</div>
			</div>
		</a>
	@endforeach


			
</div>

<div class="col-md-8 col-md-offset-2">
	<div class="panel panel-default">
	
		<div class="form-group">
			<ul class="pagination" style="margin-left:30px; margin-top:10px;">
				@for ($i = 1; $i <= $groups->lastPage(); $i++)
					<li><a href="?page={{$i}}">{{$i}}</a></li>
				@endfor
			</ul>
			<div style="display:inline; float:right; margin-right:30px; margin-top:10px;">
				<a href="/group/study/create" class="btn btn-success" role="button">스터디 만들기</a>
			</div>
		</div>
	
		<div style="margin: 20px 0; width: 100%;">
			<div style="float:left; margin: 0 10px;">
				<select class="selectpicker" data-style="btn-success" data-width="fit" name="search">
					<option>제목</option>
					<option>내용</option>
					<option>제목+내용</option>
				</select>
			</div>
			<div style="float:right; margin: 0 10px;">
				<button type="submit" class="btn btn-success" onclick="groupsearch('study')">검색</button>
			</div>
			<div style="display: block; overflow: auto;">
				<input id="searchtext" type="text" class="form-control" name="searchtext" value="{{ old('searchtext') }}" required style="padding-top:4px;">
			</div>
		</div>
	</div>
</div>
@endsection