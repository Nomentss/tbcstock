@extends('Layout.app')
@section('contents')

<div class="row">
    <div class="col-12 grid-margin">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title" style="text-align: center">Nouveau Candidats</h4>
  {{-- <form action="{{ route('candidat.import') }}" method="POST" enctype="multipart/form-data"> --}}
      @csrf
      <br>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">Nom du Candidat</label>
                  <div class="col-sm-12">
                    <input type="text" class="form-control" name="nom" />
                  </div>
                  <label style="margin-left: 45%">
                    @error('nom')
                        <span style="color: red">{{ $message }}</span>
                    @enderror
                  </label>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">Specialisation</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" name="specialisation"/>
                  </div>
                </div>                  
              </div>
              <div class="col-md-6">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">Genre</label>
                  <div class="col-sm-9">
                    <select class="custom-select" name="genre">
                      <option>Homme</option>
                      <option>Femme</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
          
            <div class="row">
              <div class="col-md-6">
                  <div class="form-group row">
                      <label class="col-sm-3 ">Postule pour poste</label>
                      <div class="col-sm-9">
                        <select class="custom-select" name="poste_postule">
                        </select>
                      </div>
                  </div>                          
              </div>
              <div class="col-md-6">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">Postulé le</label>
                  <div class="col-sm-9">
                    <input type="date" class="form-control" placeholder="dd/mm/yyyy" name="date_postule"/>
                    <label style="margin-left: 30%">
                      @error('date_postule')
                          <span style="color: red">{{ $message }}</span>
                      @enderror
                    </label>
                  </div>
                </div>
              </div>
            </div>
          
            {{-- <p class="card-description"> Address </p> --}}
              <div class="row mt-3">
                  <div class="col-md-6">
                      <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Upload File</label>
                          <div class="col-sm-9">
                              <label class="custom-file-upload">
                                  <input type="file" name="cv" />
                                  <label style="margin-left: 25%">
                                      @error('cv')
                                          <span style="color: red">{{ $message }}</span>
                                      @enderror
                                  </label>
                              </label>
                          </div>
                      </div>
                  </div>
              </div>
            <br>
        </div>
      </div>
      <button type="submit" class="btn btn-outline-secondary btn-lg btn-block">Valider</button>
  </form>
    </div>
  </div>

@endsection