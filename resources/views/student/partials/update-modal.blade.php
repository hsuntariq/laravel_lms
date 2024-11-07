<!-- Button trigger modal -->
<button type="button" class="btn btn btn-purple align-self-start" data-bs-toggle="modal" data-bs-target="#exampleModal">
    Update Info
</button>

<!-- Modal -->
<x-error />
<x-toast />
<div class="modal fade " id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Update Information</h1>
                <button type="button" class="btn-close " data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="profileForm">
                    <div>
                        <label for=" username">Username:</label>
                        <input class="form-control" type="text" id="username" name="username" />
                    </div>
                    <div>
                        <label for="password">Password:</label>
                        <input class="form-control" type="password" id="password" name="password" />
                    </div>
                    <div>
                        <label for="image">Profile Image:</label>
                        <input class="form-control" type="file" id="image" name="image" />
                    </div>
                    <button type="button" class="form-control my-2 btn btn-purple update-profile-btn">Update Profile</button>
                </form>
            </div>
        </div>
    </div>