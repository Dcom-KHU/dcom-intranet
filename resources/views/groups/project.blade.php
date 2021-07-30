@extends('layouts.app')

@section('content')
<div class="col-md-8 col-md-offset-2">
	@foreach ($groups->reverse() as $project)
		<a href="/group/project/{{$project->id}}" style="text-decoration: none;">
			<div class="panel panel-success group-list" style="height:146px;">
				<div class="panel-heading">
					{{$project->title}}
					<?php $commentcount = App\Comment::where('boardid', $project->id)->get()->count()?>
					&nbsp;&nbsp;<i class="fa fa-pencil" aria-hidden="true"></i>({{$commentcount}})
					<span style="float:right;">
						<i class="fa fa-user" aria-hidden="true"></i>
						<?php $user = App\User::where('userid', $project->userid)->first() ?>
						<span id="numberbox">{{$user->admissionyear}}</span>{{$user->realname}}
					</span>
				</div>
				<div class="panel-body" style="color:#333;">
					{{$project->content}}
					<div style="line-height:90px;">
						<?php $users = App\GroupsUsers::where('groupid', $project->id)->get() ?>
						<span>
							@foreach ($users as $user)
								<?php $id = App\User::where('userid', $user->userid)->first() ?>
								<span id="numberbox">{{$id->admissionyear}}</span>{{$id->realname}}
							@endforeach
						</span>
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
				<a href="/group/project/create" class="btn btn-success" role="button">프로젝트 만들기</a>
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
				<button type="submit" class="btn btn-success" onclick="groupsearch('project')">검색</button>
			</div>
			<div style="display: block; overflow: auto;">
				<input id="searchtext" type="text" class="form-control" name="searchtext" value="{{ old('searchtext') }}" required style="padding-top:4px;">
			</div>
		</div>
	</div>
</div>
@endsection