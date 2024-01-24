@push('styles')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.9.179/pdf_viewer.min.js"
        integrity="sha512-F7+TyES+XbRxRxBiu+koc73FGtlSDL2/Brza3G5HScjRCP8GbL4P6BukydYlVmUTd+Si+7K98RNnninDA89JPA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@endpush
<div>
    <div class="page-header">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        {{ $event->title ?? '' }} - Layout
                    </h2>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            <div class="row">
                <div class="col-md-12">
                    <div class="mx-auto">

                        <div class="col-md-12 mx-auto">
                            @include('includes.alerts')
                        </div>

                        @php
                            $layoutPath = $event->_meta['layout'] ?? '';
                        @endphp
                        <span id="layoutPath" hidden>{{ asset('storage/' . $layoutPath) }}</span>
                        <div id="pdf-viewer" style="height: 100vh;width: 100%; overflow: auto;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.9.179/pdf.min.js"
        integrity="sha512-9jr6up7aOKJkN7JmtsxSdU+QibDjV6m6gL+I76JdpX1qQy8Y5nxAWVjvKMaBkETDC3TP3V2zvIk+zG7734WqPA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {


            var pdfUrl = document.getElementById('layoutPath').innerText;
            var loadingTask = pdfjsLib.getDocument(pdfUrl);
            // console.log(loadingTask);

            loadingTask.promise.then(pdf => {
                pdf.getPage(1).then(page => {
                    const scale = 1.5;
                    const viewport = page.getViewport({
                        scale
                    });
                    // console.log(scale);
                    // console.log(viewport);

                    const canvas = document.createElement('canvas');
                    const context = canvas.getContext('2d');
                    canvas.height = viewport.height;
                    canvas.width = viewport.width;

                    const renderContext = {
                        canvasContext: context,
                        viewport: viewport
                    };

                    document.getElementById('pdf-viewer').appendChild(canvas);
                    page.render(renderContext);
                });
            });
        });
    </script>
@endpush
