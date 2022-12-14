@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    Working Hours Calculation
                </div>

                <div class="card-body">
                <img class="" src="success.png" alt="Card image cap" width="50px" height="50px" style="margin:auto; margin-top:10px;">
                <br><br>
                <h5 class="card-title">File Uploaded Successfully</h5>
                <!-- <p class="card-text">Click on the button below to download your formatted file.</p> -->
                <p>Select the dates below you want to download sheet between.</p>

                    <form method="post" action="{{ route('export') }}" enctype="multipart/form-data">
                    @csrf
                    <input class="form control" style="width:160px;" type="date" name="from"  value="" required>

                    <input class="form control" style="width:160px;" type="date" name="to"  value="" required>

                    <br><br>
                    <button type="submit" class="btn btn-primary">Download</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection