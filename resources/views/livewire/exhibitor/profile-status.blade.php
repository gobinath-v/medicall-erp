<div>
    <div class="row">
        <div class="col-md-3 pt-1 ps-3">
            <h5 class="text-capitalize">Profile</h5>
        </div>
        <div class="col-md-9 mb-3">
            <div class="progress" style="height:25px;">
                <span class="progress-bar bg-yellow" role="progressbar" aria-valuenow="70" aria-valuemin="0"
                    aria-valuemax="100" style="width:{{ $filledFields ?? 0 }}%">
                    <span class="fs-4">{{ $filledFields ?? 0 }}%</span>
                </span>
            </div>
        </div>
    </div>
</div>
