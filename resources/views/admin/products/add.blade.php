@extends('admin.MasterAdmin')
@section('title',' Agregar Producto')

@section('breadcrumb')
<li class="breadcrumb-item">
	<a href="{{url('/admin/products')}}"><i class="fas fa-boxes"></i> Productos</a>
</li>
<li class="breadcrumb-item">
	<a href="{{url('/admin/product/add')}}"><i class="fas fa-plus"></i> Agregar Productos</a>
</li>
@endsection
@section('content')
<div class="container-fluid">
	<div class="panel shadow">
		<div class="header">
			<h2 class="title"><i class="fas fa-plus"></i> Agregar Productos</h2>
		</div>
		<div class="inside mar8">
			{!! Form::open(['url'=>'/admin/product/add', 'files'=>true]) !!}

			<div class="row mtop16">
				<div class="col-md-6">
					<label for="name"> Nombre del producto:</label>
						<div class="input-group">
							<div class="input-group-prepend">
							   	<span class="input-group-text" id="basic-addon1">
							   		<i class="fas fa-keyboard"></i>
							   	</span>
							</div>
							{!! Form::text('name',null, ['class'=>'form-control '])!!}
						</div>
				</div>
				<div class="col-md-3">
					<label for="category_id"> Categoria Producto:</label>
					<div class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text" id="basic-addon1">
								<i class="fas fa-hand-pointer"></i>
							</span>
						</div> 
					{!! Form::select('category_id', $cats , 0 , ['class'=>'custom-select']) !!}

					</div>
				</div>

				<div class="col-md-3">
				<label for="img"> Imagen Destacada:</label>
					<div class="custom-file">
						{!! Form::file('img', ['class'=>'custom-file-input ','id'=>'customFile','accept'=>'image/*']) !!}
					  	<label class="custom-file-label" for="customFile">escoja un archivo</label>
					</div>
						
				</div>	
			</div>
			
			<div class="row mtop16">
				<div class="col-md-3 ">
				<label for="price"> Precio:</label>
					<div class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text" id="basic-addon1">
							<i class="fas fa-keyboard"></i>
							</span>
						</div>
							{!! Form::number('price',null, ['class'=>'form-control ','min'=>'0.00','step'=>'any'])!!}
					</div>
				</div>
	
				<div class="col-md-3">
						<label for="in_discount"> ¿Esta en descuento?</label>
					<div class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text" id="basic-addon1">
							   		<i class="fas fa-keyboard"></i>
							</span>
						</div> 
						{!! Form::select('in_discount',['0' => 'No', '1' => 'Si'], 0 , ['class'=>'custom-select','id'=>'indiscount']) !!}
					</div>
				</div>

				<div class="col-md-3">
						<label for="discount"> Desuento</label>
					<div class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text" id="basic-addon1">
							   		<i class="fas fa-keyboard"></i>
							</span>
						</div> 
							{!! Form::number('discount', 0.00 ,['class'=>'form-control','min'=>'0.00','step'=>'any'])!!}
					</div>
				</div>
			</div>	

				
				<div class="row mtop16">
					<div class="col-md-12">
						<label for="content"> Descripcion:</label>
						{!! Form::textarea('content', null,['class' => 'form-control','id'=>'editor'])!!}
					</div>
				</div>
				<div class="row mtop16">
					<div class="col-md-12">
						{!! Form::submit('Guardar',['class'=>'btn btn-success']) !!}
					</div>
				</div>
			{!!Form::close()!!}			
		</div>
	</div>
</div>
@endsection