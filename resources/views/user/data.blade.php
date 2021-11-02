<form action="categorias" method="POST">
          @csrf
          <div>
          <a class="btn btn-outline-primary" href="/registro">AGREGAR</a>
          </div>  
      </form>
      <div class="mb-2 mt-3">
          <h5>Lista de usuarios</h5>
      </div>
      <ul class="list-group">
            @if(count($users)==0)
            <li class="list-group-item text-center">
                Aún no hay elementos agregados.
            </li>
            @endif
            @foreach($users as $user)
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-md-8">
                        {{$user->name}}    
                        </div>
                        <div class="col-md-2 d-flex justify-content-center">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-id="{{$user->id}}" data-name="{{$user->name}}" data-type="edit">
                            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-pencil-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z"/>
                            </svg>  
                                EDITAR
                            </button>
                        </div>
                        <div class="col-md-2 d-flex justify-content-center">
                        <button type="button" class="btn btn-outline-danger btn-sm" data-id="{{$user->id}}" data-type="delete">
                            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-trash-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5a.5.5 0 0 0-1 0v7a.5.5 0 0 0 1 0v-7z"/>
                            </svg>   
                            ELIMINAR
                        </button>
                        </div>
                    </div>
                </li>
            @endforeach
      </ul>
    </div>
</div>
