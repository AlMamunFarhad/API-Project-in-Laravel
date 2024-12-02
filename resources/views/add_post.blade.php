@extends('layout')

@section('content')
    <div class="row justify-content-center vh-100 align-content-center">

        <div class="col-md-6 card p-4">
            <div class="heading mb-4 mt-5">
                <h1>Add Post</h1>
            </div>
            <form id="addForm">
                @csrf
                <div class="form-group mb-3">
                    <input type="file" class="form-control" name="image" id="image">
                </div>
                <div class="form-group mb-3">
                    <input type="text" class="form-control " name="title" placeholder="title" id="title">
                </div>
                <div class="form-group mb-3">
                    <textarea name="description" cols="30" rows="10" placeholder="Description" class="form-control"
                        id="description"></textarea>
                </div>
                <input type="submit" class="btn btn-success" value="Submit" id="addBtn">
                <a href="{{ route('viewPosts') }}" class="btn btn-secondary">Back</a>
            </form>
        </div>
    @endsection

@push('scripts')
<script>
    let addForm = document.querySelector('#addForm');

    addForm.onsubmit = async (e) => {
        e.preventDefault();

        let token = localStorage.getItem('api_token');

        const title = document.querySelector("#title").value;
        const description = document.querySelector("#description").value;
        const image = document.querySelector("#image").files[0];



        var formData = new FormData();
        formData.append('title', title);
        formData.append('description', description);
        formData.append('image', image);

        let response = await fetch('/api/posts', {
                method: 'POST',
                body: formData,
                headers: {
                    'Authorization': `Bearer ${token}`,
                }
            })
            .then(response => response.json())
            .then(data => {
                window.location.href = "http://localhost:8000/view/posts";
            });                   
    }

</script> 
@endpush    
