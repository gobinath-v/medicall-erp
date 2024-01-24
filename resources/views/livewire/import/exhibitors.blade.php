<div>
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        Import Exhibitors
                    </h2>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            @include('includes.alerts')
            <div class="row row-cards">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <p class="card-text">
                                Import exhibitors from a XLSX file. Sample file can be downloaded <a
                                    href="{{ asset('assets/exhibitors.xlsx') }}"
                                    target="_blank
                                    download="">here</a>.
                            </p>
                            <div class="">
                                <input type="file"id="import-file" class="form-control">
                            </div>

                            <div class="mt-3">
                                <button wire:click="$dispatch('processData')" class="btn btn-primary">Import</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



@push('scripts')
    <script src="{{ asset('/libs/sheetjs/xlsx.full.min.js') }}"></script>

    <script>
        if (typeof require !== 'undefined') XLSX = require('xlsx');
        jQuery(document).ready(function() {

            Livewire.on('processData', () => {

                const attachmentField = document.getElementById("import-file");
                const file = attachmentField ? attachmentField.files[0] : [];
                const reader = new FileReader();
                reader.onload = function(e) {
                    const data = e.target.result;
                    let workbook;
                    try {
                        workbook = XLSX.read(data, {
                            type: "array",
                            cellDates: true,
                        });
                    } catch (e) {
                        alert(e);
                        return false;
                    }

                    /** Get workbook first sheet name the workbook sheets */
                    let workbookSheetName = workbook.SheetNames[0];

                    /** Get worksheet by sheet name */
                    let workSheet = workbook.Sheets[workbookSheetName];

                    let readArgs = {
                        defval: "",
                        header: 1,
                        blankrows: false,
                    };

                    let jsonData = XLSX.utils.sheet_to_json(workSheet, readArgs);

                    const importData = {
                        data: jsonData
                    }
                    console.log('importData : ', importData);
                    @this.dispatch('importingData', importData);
                }
                reader.readAsArrayBuffer(file);
            });
        });
    </script>
@endpush
