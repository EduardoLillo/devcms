@extends('admin.MasterAdmin')
@section('title','Editar Usuario')

@section('breadcrumb')
<li class="breadcrumb-item">
	<a href="{{url('/admin/users')}}"><i class="fas fa-users"></i> Usuarios</a>
</li>
@endsection

@section('content')
<div class="container-fluid">
	<div class="page_user">
		<div class="row">
			<div class="col-md-4">
				<div class="panel shadow">
					<div class="header">
						<h2 class="title"><i class="far fa-user-circle" style="font-size: 22px;"></i>  Informacion</h2>
					</div>
					<div class="inside">
						<div class="mini_profile">
							@if(is_null($u->avatar))
							<img src="{{ url('/static/images/default-avatar.png') }}" class="avatar">
							@else
							<img src="{{ url('/uploads/users/'.$u->id.'/'.$u->avatar) }}"  class="avatar">
							@endif
							<div class="info">
								<span class="title"><i class="far fa-address-card"></i> Nombre:</span>
								<span class="text">{{ $u->name}} {{ $u->lastname}} </span>
								<span class="title"><i class="fas fa-user-tie"></i> Estado de Usuario:</span>
								<span class="text"> {{ getUserStatusArray(null, $u->status) }} </span>
								<span class="title"><i class="far fa-envelope"></i> Correo Electrónico:</span>
								<span class="text"> {{ $u->email}} </span>
								<span class="title"><i class="far fa-calendar-alt"></i> Fecha de Registro:</span>
								<span class="text"> {{ $u->created_at }} </span>	
								<span class="title"><i class="fas fa-user-shield"></i> Role de Usuario:</span>
								<span class="text"> {{ getUserRoleArray(null, $u->rol) }} </span>
							</div>
							@if($u->status == "100")
							<a href="{{ url('/admin/user/'.$u->id.'/banned') }}" class="btn btn-success">Activar Usuario</a>
							@else
							<a href="{{ url('/admin/user/'.$u->id.'/banned') }}" class="btn btn-danger">Suspender Usuario</a>
							@endif
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-8">
				<div class="panel shadow">
					<div class="header">
						<h2 class="title"><i class="fas fa-user-edit"></i> Editar Iformacion</h2>
					</div>
					<div class="inside">
					
					</div>
				</div>
			</div>
		</div>
	</div>	
</div>
@endsection