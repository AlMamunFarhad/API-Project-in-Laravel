@extends('layout')

@section('content')
    <div class="mainContainer mt-5">
        <div class="card p-4">
            <div class="all-users mb-4 mt-3">
                <h1>All Posts</h1>
            </div>
            <div class="row">
                <div class="col-md-12 justify-content-center">
                    <div class="add-post-btn">
                        <a href="{{ route('addPost') }}" class="btn btn-primary">Add Post</a>
                        <button class="btn btn-success" id="logoutBtn">Logout</button>
                    </div>
                    <div id="postsContainer"></div>
                </div>
            </div>
            <!-- Single Post Modal -->
            <div class="modal fade" id="singlePostModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                aria-labelledby="singlePostLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="singlePostLabel">View Post</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Update Post Modal --}}

            <div class="modal fade" id="updatePostModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                aria-labelledby="updatePostModal" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="updatePostModal">Update Post</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form id="updateForm">
                            <div class="modal-body">
                                <input type="hidden" id="postId" value="">
                                <div class="form-group mb-2">
                                    <label for="title">Title</label>
                                    <input type="text" class="form-control" name="title" id="title" value="">
                                </div>
                                <div class="form-group mb-2">
                                    <label for="email">Description</label>
                                    <textarea name="description" id="description" cols="30" rows="3" class="form-control" value=""></textarea>
                                </div>
                                <div class="form-group mb-2">
                                    <img src="" alt="" id="showImg" class="img-fluid" width="150">
                                    <input type="file" class="form-control" name="image" id="image">
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-primary" id="updateBtn" type="submit">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- ** Js Start ** --}}
    <script>
        document.querySelector("#logoutBtn").addEventListener('click', function() {

            const token = localStorage.getItem('api_token');

            fetch('/api/logout', {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                    }
                })
                .then(response => response.json())
                .then(data => {
                    window.location.href = "http://localhost:8000/";
                });
        });

        function loadData() {

            const token = localStorage.getItem('api_token');

            fetch('/api/posts', {
                    method: 'GET',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                    }
                })
                .then(response => response.json())
                .then(data => {
                    var allPosts = data.data.posts;
                    const postContainer = document.querySelector("#postsContainer");

                    var tableData = `<table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>Image</th>
                                                <th>Title</th>
                                                <th>Description</th>
                                                <th>View</th>
                                                <th>Update</th>
                                                <th>Delete</th>
                                            </tr>
                                        </thead>`;
                    allPosts.forEach(post => {
                        tableData += `<tbody>
                                            <tr>
                                                <td><img src="/uploads/${post.image}" width="150" alt=""></td>
                                                <td>${post.title}</td>
                                                <td>${post.description}</td>
                                                <td>
                                                    <a href="" class="btn btn-primary btn-sm" data-bs-post="${post.id}" data-bs-toggle="modal" data-bs-target="#singlePostModal">View</a>
                                                </td>
                                                <td>
                                                    <a href="" class="btn btn-warning btn-sm" data-bs-post="${post.id}" data-bs-toggle="modal" data-bs-target="#updatePostModal">Update</a>
                                                </td>
                                                <td>
                                                <button class="btn btn-danger btn-sm" type="submit" onclick="deletePost(${post.id})">Delete</button>
                                                </td>
                                            </tr>
                                        </tbody>`
                    });

                    tableData += `</table>`;
                    postContainer.innerHTML = tableData;
                });
        }
        loadData();

        // Show single post

        var singleModal = document.querySelector("#singlePostModal");

        if (singleModal) {
            singleModal.addEventListener('show.bs.modal', event => {
                const modalBody = document.querySelector("#singlePostModal .modal-body");
                modalBody.innerHTML = "";
                // Button that triggered the modal
                const button = event.relatedTarget

                // Extract info from data-bs-* attributes
                const post_id = button.getAttribute('data-bs-post')

                const token = localStorage.getItem('api_token');

                fetch(`/api/posts/${post_id}`, {
                        method: 'GET',
                        headers: {
                            'Authorization': `Bearer ${token}`,
                            'contentType': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        //   console.log(data.data.post);

                        const post = data.data.post[0];

                        modalBody.innerHTML = `
                                                <div class="d-flex flex-column justufy-content-center align-items-center">
                                                <img src="/uploads/${post.image}" width="150" class="rounded">
                                                <h5 class="mt-3">${post.title}</h5>
                                                 <p>${post.description}"</p>
                                                </div>
                                                `;

                    });
            });
        }

        //Show Update Post 
        var updateModal = document.querySelector("#updatePostModal");

        if (updateModal) {
            updateModal.addEventListener('show.bs.modal', event => {
                const modalBody = document.querySelector("#updatePostModal .modal-body");

                // Button that triggered the modal
                const button = event.relatedTarget

                // Extract info from data-bs-* attributes
                const post_id = button.getAttribute('data-bs-post')

                const token = localStorage.getItem('api_token');

                fetch(`/api/posts/${post_id}`, {
                        method: 'GET',
                        headers: {
                            'Authorization': `Bearer ${token}`,
                            'contentType': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        //   console.log(data.data.post);

                        const post = data.data.post[0];

                        const postId = document.querySelector("#postId").value = post.id;
                        const postTitle = document.querySelector("#title").value = post.title;
                        const PostDescription = document.querySelector("#description").value = post.description;
                        const PostImage = document.querySelector("#showImg").src = `/uploads/${post.image}`;

                    });
            });
        }
        // Update add form
        let updateForm = document.querySelector('#updatePostModal');

        updateForm.onsubmit = async (e) => {
            e.preventDefault();

            let token = localStorage.getItem('api_token');

            const id = document.querySelector("#postId").value;
            const title = document.querySelector("#title").value;
            const description = document.querySelector("#description").value;

            var formData = new FormData();
            formData.append('id', id);
            formData.append('title', title);
            formData.append('description', description);
            if (!document.querySelector("#image").files[0] == "") {
                const image = document.querySelector("#image").files[0];
                formData.append('image', image);
            }

            let response = await fetch(`/api/posts/${id}`, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'X-HTTP-Method-Override': 'PUT'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                    window.location.href = "http://localhost:8000/view/posts";
                });

        }
        // Delete Post
        async function deletePost(postId) {

            let token = localStorage.getItem('api_token');

            let response = await fetch(`/api/posts/${postId}`, {
                    method: 'DELETE',
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                })
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                    window.location.href = "http://localhost:8000/view/posts";
                });
        }
    </script>
@endpush
