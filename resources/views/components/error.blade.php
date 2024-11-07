<!-- <div class="error position-fixed  align-items-center" style="top: 10px;left:50%;transform: translateX(-50%)">
    <div class="error__icon">
        <svg fill="none" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
            <path
                d="m13 13h-2v-6h2zm0 4h-2v-2h2zm-1-15c-1.3132 0-2.61358.25866-3.82683.7612-1.21326.50255-2.31565 1.23915-3.24424 2.16773-1.87536 1.87537-2.92893 4.41891-2.92893 7.07107 0 2.6522 1.05357 5.1957 2.92893 7.0711.92859.9286 2.03098 1.6651 3.24424 2.1677 1.21325.5025 2.51363.7612 3.82683.7612 2.6522 0 5.1957-1.0536 7.0711-2.9289 1.8753-1.8754 2.9289-4.4189 2.9289-7.0711 0-1.3132-.2587-2.61358-.7612-3.82683-.5026-1.21326-1.2391-2.31565-2.1677-3.24424-.9286-.92858-2.031-1.66518-3.2443-2.16773-1.2132-.50254-2.5136-.7612-3.8268-.7612z"
                fill="#393a37"></path>
        </svg>
    </div>
    <div class="error__title">

    </div>
    <div class="error__close">
        <svg height="20" viewBox="0 0 20 20" width="20" xmlns="http://www.w3.org/2000/svg">
            <path
                d="m15.8333 5.34166-1.175-1.175-4.6583 4.65834-4.65833-4.65834-1.175 1.175 4.65833 4.65834-4.65833 4.6583 1.175 1.175 4.65833-4.6583 4.6583 4.6583 1.175-1.175-4.6583-4.6583z"
                fill="#393a37"></path>
        </svg>
    </div>
</div> -->

<div class="underlay error  position-fixed top-0 justify-content-center align-items-center" style="height:100vh;width:100vw;z-index:222222">
    <div class="message-box alert alert-danger position-fixed bg-white rounded-3 p-3" style=" box-shadow: 0 4px 15px rgba(255, 0, 0, 0.5),0 8px 30px rgba(255, 0, 0, 0.3);top:50%;left:50%;">
        <i class="bi bi-x-lg fs-2 position-absolute end-0 p-3 top-0" style="cursor:pointer" onclick="closeErrorMessages()"></i><br>
        <div class="d-flex align-items-center gap-3">
            <i class="bi bi-ban fs-2"></i>
            <h2>Error!</h2>
        </div>
        <p class="alert-danger fw-semibold">
            Please correct the following errors to proceed
        </p>
        <ol class="error__title">

        </ol>
    </div>
</div>