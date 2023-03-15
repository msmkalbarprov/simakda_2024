@extends('template.app')
@section('title', 'Dashboard | SIMAKDA')

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">{{'Dashboard'}}</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">{{'App'}}</a></li>
                    <li class="breadcrumb-item active">{{'Dashboard'}}</li>
                </ol>
            </div>

        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-md-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="float-end mt-2">
                    <div id="total-revenue-chart" data-colors='["--bs-primary"]'></div>
                </div>
                <div>
                    <h6 class="mb-1 mt-1 ">Rp<span >{{nilai($data_pendapatan->pendapatan)}}</span></h6>
                    <p class="text-mute mb-0">Pendapatan</p>
                </div>
               
            </div>
        </div>
    </div> <!-- end col-->

    <div class="col-md-6 col-xl-3">
        <div class="card">
            <div class="card-body ">
                <div class="float-end mt-2">
                    <div id="growth-chart" data-colors='["--bs-warning"]'></div>
                    
                </div>
                <div>
                    <h6 class="mb-1 mt-1 ">Rp<span >{{ rupiah($data_belanja->belanja)}}</span></h6>
                    <p class="text-mute mb-0">Belanja</p>
                </div>
                
            </div>
        </div>
    </div> <!-- end col-->

    <div class="col-md-6 col-xl-3">
        <div class="card">
            <div class="card-body ">
                <div class="float-end mt-2">
                    <div id="total-revenue-chart2" data-colors='["--bs-primary"]'></div>
                </div>
                <div>
                    <h6 class="mb-1 mt-1 ">Rp<span >{{rupiah($data_pem_terima->pem_terima)}}</span></h6>
                    <p class="text-mute mb-0">Pembiayaan Penerimaan</p>
                </div>
                
            </div>
        </div>
    </div> <!-- end col-->

    <div class="col-md-6 col-xl-3">

        <div class="card">
            <div class="card-body">
                <div class="float-end mt-2 ">
                    <div id="total-revenue-chart4" data-colors='["--bs-warning"]'> </div>
                    
                </div>
                <div>
                    <h6 class="mb-1 mt-1 ">Rp<span >{{rupiah($data_pem_keluar->pem_keluar)}}</span></h6>
                    <p class="text-mute mb-0">Pembiayaan Pengeluaran</p>
                </div>
                
            </div>
        </div>
    </div> <!-- end col-->
</div> <!-- end row-->

{{-- ALERT START--}}
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    @php
        $pengumuman1 = pengumuman_top1();
    @endphp
    <i class="fa fa-info-circle"> {{$pengumuman1->judul}}</i>
    
    <p>{!!strip_tags($pengumuman1->isi,'<ul><ul/><li></li>')!!}</p>
    <button type="button" class="btn btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
{{-- ALERT END --}}
<div class="row">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">{{'Pagu Anggaran'}}</h4>
                <div id="chartdiv" ></div>
                {{-- <div id="pie_chart" class="apex-charts" dir="ltr"></div> --}}
            </div>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">{{'Pengeluaran'}}</h4>
                <div id="chartdiv2"></div>
                {{-- <div id="donut_chart" class="apex-charts"  dir="ltr"></div> --}}
            </div>
        </div>
    </div>
</div>

<!-- apexcharts -->
<script src="{{asset('template/assets/libs/apexcharts/apexcharts.min.js')}}"></script>

<script src="{{asset('template/assets/js/pages/dashboard.init.js')}}"></script>
        <!-- apexcharts init -->

{{-- AMCHART --}}
<style>
    #chartdiv {
      width: 100%;
      height: 300px;
    }
    body {
  font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
}
    </style>
    
    <!-- Resources -->
    <script src="{{asset('js/amchart/core.js')}}"></script>
    <script src="{{asset('js/amchart/charts.js')}}"></script>
    <script src="{{asset('js/amchart/animated.js')}}"></script>
    <script src="{{asset('js/amchart/dataviz.js')}}"></script>
    <script src="{{asset('js/amchart/kelly.js')}}"></script>
    
    
    <!-- Chart code -->
    <script>
  
    // Data
    let pendapatan  = parseFloat("{{ $data_pendapatan->pendapatan }}");
    let belanja     = parseFloat("{{ $data_belanja->belanja }}");
    let pem_terima  = parseFloat("{{ $data_pem_terima->pem_terima }}");
    let pem_keluar  = parseFloat("{{ $data_pem_keluar->pem_keluar }}");
    
    
  
    am4core.useTheme(am4themes_animated);
am4core.useTheme(am4themes_dataviz);

// Create chart instance
var chart = am4core.create("chartdiv", am4charts.PieChart);

// Add data
chart.data = [{
  "Akun": "Pendapatan",
  "nilai": pendapatan,
  "color": am4core.color("#18723f")
}, {
  "Akun": "Belanja",
  "nilai": belanja,
  "color": am4core.color("#3b9a56")
}, {
  "Akun": "Penerimaan Pembiayaan",
  "nilai": pem_terima,
  "color": am4core.color("#6fbf76")
}, {
  "Akun": "Pengeluaran Pembiayaan",
  "nilai": pem_keluar,
  "color": am4core.color("#eff8b9")
}];

// Add and configure Series
var pieSeries = chart.series.push(new am4charts.PieSeries());
pieSeries.dataFields.value = "nilai";
pieSeries.dataFields.category = "Akun";
pieSeries.slices.template.propertyFields.fill = "color";

chart.innerRadius = am4core.percent(40);
chart.hiddenState.properties.innerRadius = am4core.percent(0);
chart.hiddenState.properties.radius = am4core.percent(100);

// Let's cut a hole in our Pie chart the size of 40% the radius
// chart.innerRadius = am4core.percent(40);
pieSeries.hiddenState.properties.endAngle = -90;

// Set up fills
pieSeries.slices.template.fillOpacity = 1;

var hs = pieSeries.slices.template.states.getKey("hover");
hs.properties.scale = 1;
hs.properties.fillOpacity = 0.5;

var label = pieSeries.createChild(am4core.Label);
label.text = "{{tahun_anggaran()}}";
label.horizontalCenter = "middle";
label.verticalCenter = "middle";
label.fontSize = 30;

// {{-- AMCHART --}}
</script>
<style>
    #chartdiv2 {
      width: 100%;
      height: 300px;
    }
    </style>
    
    
    <!-- Chart code -->
    <script>
        
            let penagihan   = parseFloat("{{ $data_penagihan->penagihan }}");
            let totaltagih   = parseFloat("{{ $data_penagihan->totaltagih }}");
            let spp         = parseFloat("{{ $data_spp->spp }}");
            let totalspp         = parseFloat("{{ $data_spp->total }}");
            let spm         = parseFloat("{{ $data_spm->spm }}");
            let totalspm         = parseFloat("{{ $data_spm->total }}");
            let sp2d        = parseFloat("{{ $data_sp2d->sp2d }}");
            let totalsp2d        = parseFloat("{{ $data_sp2d->total }}");
        // Apply chart themes
am4core.useTheme(am4themes_animated);
am4core.useTheme(am4themes_kelly);

// Create chart instance
var chart = am4core.create("chartdiv2", am4charts.XYChart);

// Add data
chart.data = [{
  "jenis": "penagihan",
  "jumlah": totaltagih,
  "nilai": penagihan.toFixed(2)
}, {
  "jenis": "SPP",
  "jumlah": totalspp,
  "nilai": spp.toFixed(2)
}, {
  "jenis": "SPM",
  "jumlah": totalspm,
  "nilai": spm.toFixed(2)
}, {
  "jenis": "SP2D",
  "jumlah": totalsp2d,
  "nilai": sp2d.toFixed(2)
}];

// Create axes
var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
categoryAxis.dataFields.category = "jenis";
categoryAxis.title.text = "Berkas";

// First value axis
var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
valueAxis.title.text = "jumlah Berkas";

// Second value axis
var valueAxis2 = chart.yAxes.push(new am4charts.ValueAxis());
valueAxis2.title.text = "Nilai";
valueAxis2.renderer.opposite = true;


// First series
var series = chart.series.push(new am4charts.ColumnSeries());
series.dataFields.valueY = "jumlah";
series.dataFields.categoryX = "jenis";
series.name = "Jumlah";
series.tooltipText = "{name}: [bold]{valueY}[/] Berkas";

// Second series
var series2 = chart.series.push(new am4charts.LineSeries());
series2.dataFields.valueY = "nilai";
series2.dataFields.categoryX = "jenis";
series2.name = "Nilai";
series2.tooltipText = "{name}: Rp[bold]{valueY}[/]";
series2.strokeWidth = 3;
series2.yAxis = valueAxis2;

series.columns.template.fill = am4core.color("#3b9a56");

// Add legend
chart.legend = new am4charts.Legend();

// Add cursor
chart.cursor = new am4charts.XYCursor();
        </script>

@endsection
