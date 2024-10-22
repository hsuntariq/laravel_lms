<!-- Button trigger modal -->
<button type="button" class="btn btn btn-purple align-self-start" data-bs-toggle="modal" data-bs-target="#exampleModal">
    Update Info
</button>

<!-- Modal -->
<div class="modal fade " id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Update Information</h1>
                <button type="button" class="btn-close " data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/update-info/1" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" name="username" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="username">Image</label>
                        <input type="file" name="image" class="form-control update-image">
                    </div>
                    <img class="image-preview border my-2" width="100%" height="300px" style="object-fit: contain"
                        src="Assign Mate user updated image" />

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-purple">Save changes</button>
                    </div>
            </div>
            </form>
        </div>
    </div>
</div>
