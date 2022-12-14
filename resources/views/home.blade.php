@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <!-- Admin Panel -->
                    Working Hours Calculation
                </div>

                <div class="card-body">
                    <!-- @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }} -->

                    <form method="post" action="{{ route('attendance.store') }}" enctype="multipart/form-data">
                    @csrf
                    <label class="form-label" for="File" required>Upload Attendance Sheet</label>
                    <input type="file" class="form-control" id="file" name="file"  required/>
                    <br>
                    <button type="submit" class="btn btn-primary" name="btn" id="btn">Upload File</button>
                    <a href="{{route('report.index')}}" class="btn btn-primary">Generate Report</a>

                    </form>

                    <div id="loader" style="display:none;">
                        <h2>Please Wait !</h2>
                        <p>Your request is being processed.</p>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

<script>

$(document).ready(function(){
$( "#btn" ).click(function() {
myFunction(this);
});

function myFunction(div) {
$("#loader").toggle();
$(div).toggle();
}
});
</script>

@endsection
