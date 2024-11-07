<aside class="card border-0 rounded-3 shadow p-xl-5 p-3">
    <h5 class="mb-3">Overall Class Performance</h5>
    <h6 class="text-secondary mb-2">
        Skill Profeciancy
    </h6>
    <section class="d-flex align-items-center gap-2">
        <div class="graph w-50 d-flex justify-content-center align-items-center" style='height:150px'>
            <img width="150px" class=" loading-chart mx-auto" src="{{ asset('assets/images/loading.gif') }}" alt="Loading...">
            {{-- graph goes here --}}
            <canvas id="doughnutChartCanvas2" style="display: none;"></canvas>
        </div>

        <section class="profeciency-details">
            <div class="row align-items-center">
                <div class="col-6">
                    <div class="d-flex flex-column">
                        <p class="text-sm">Advanced</p>
                        <div class="d-flex align-items-center gap-2">
                            <div class="dot bg-success rounded-circle"></div>
                            <h6 class="m-0 excelling-percentage"></h6>
                        </div>
                    </div>
                </div>

                <div class="col-6">
                    <div class="d-flex flex-column">
                        <p class="text-sm">Average</p>
                        <div class="d-flex align-items-center gap-2">
                            <div class="dot bg-warning rounded-circle"></div>
                            <h6 class="m-0 average-percentage"></h6>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="d-flex flex-column">
                        <p class="text-sm">Below avg</p>
                        <div class="d-flex align-items-center gap-2">
                            <div class="dot bg-danger rounded-circle"></div>
                            <h6 class="m-0 struggling-percentage"></h6>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </section>