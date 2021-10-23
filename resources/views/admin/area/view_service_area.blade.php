
<!DOCTYPE html>
<html>
<head>
    <title>::Serving Area</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&display=swap" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Nunito:wght@300;400&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Nunito:wght@300;400&display=swap" rel="stylesheet">

    <link href="{{asset('css/service_area.css')}}" rel="stylesheet">


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <meta name="viewport" content="width=500, initial-scale=1">

</head>
<body>
<div class="main_wrapper">
    <div class="width_100 service_area">
        <h1 class="heading f_20 margin_zero flex_flow align_center"> <span style="margin-right: 10px;"><img src="{{asset('admin-theme/locatio_01.png')}}" width="18"/></span> <label>We are active in below cities:</label></h1>
        <ul class="city_list">
            @foreach($cities_arr as $key=>$val)
            <li>
                <a href="javascript:void(0)" onclick='$(".servicing_areaxx").hide();$("#{{str_replace(' ', '_', $key)}}").show()'>{{$key}}</a>
            </li>
            @endforeach
        </ul>


        @php
        $i=0;
        @endphp
        @foreach($cities_arr as $key=>$val)

        <div class="servicing_areaxx width_100" id="{{str_replace(' ', '_', $key)}}" @if($i>0)style="display:none" @endif>
            <h1 class="heading f_16 margin_zero flex_flow align_center"> <span style="margin-right: 10px;"><img src="{{asset('admin-theme/locatio_01.png')}}" width="18"/></span> <label>Servicable area</label></h1>
            <ul class="servicble_area">
            @foreach($val as $v)
                    <li>{{$v}}</li>
            @endforeach
            </ul>

        </div>
            @php
                $i++;
            @endphp
        @endforeach
    </div>
</div>
</body>
</html>
