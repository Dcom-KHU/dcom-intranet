@extends('layouts.app')

@section('content')
<div class="col-md-8 col-md-offset-2">
	<div class="panel panel-default">
		<div class="panel-heading">
			@if ($type == 'project') 프로젝트@elseif($type == 'study') 스터디@endif 수정
		</div>
		<div class="panel-body">
			<?php $group=App\Group::where('boardid',$boardid)->first()  ?>
			<form class="form-horizontal" role="form" method="POST" action="/group/{{$group->type}}/{{$group->boardid}}/modify">
				{{ csrf_field() }}
				
				<input type="hidden" name="type" value="{{$type}}">
				
				<div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
					<div class="col-md-12">
						<label for="title">
							@if ($type == 'project') 프로젝트 @elseif($type == 'study') 스터디 @endif이름
						</label>
						<input id="title" type="title" class="form-control" name="title" value="{{ $group->title }}" required autofocus>
						 @if ($errors->has('title'))
							<span class="help-block">
								<strong>{{ $errors->first('title') }}</strong>
							</span>
						@endif
					</div>
				</div>
				

				<div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
					<div class="col-md-12">
						<label for="description">
							@if ($type == 'project') 프로젝트 @elseif($type == 'study') 스터디 @endif설명
						</label>
						<input id="description" type="description" class="form-control" name="description" value="{{ $group->description }}" required>
						 @if ($errors->has('description'))
							<span class="help-block">
								<strong>{{ $errors->first('description') }}</strong>
							</span>
						@endif
					</div>
				</div>
				
				
				<div class="form-group{{ $errors->has('git') ? ' has-error' : '' }}">
					<div class="col-md-12">
						<label for="git">
							git 주소
						</label>
						<input id="git" type="git" class="form-control" name="git" value="{{ $group->git }}">
						 @if ($errors->has('git'))
							<span class="help-block">
								<strong>{{ $errors->first('git') }}</strong>
							</span>
						@endif
					</div>
				</div>
				

				<div class="form-group">
					<div class="col-md-12">
					<span class="addbutton">
						<button type="submit" class="btn btn-success" style="float:right;">
							완료
						</button>
					</span>
					</div>
				</div>

			</form>
		</div>
	</div>
</div>
@endsection