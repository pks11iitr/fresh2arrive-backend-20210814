
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
    <meta name="viewport" content= "width=device-width, initial-scale=1.0">


    <style>
        .heading{font-family: 'Montserrat', sans-serif;}
        .main_wrapper{margin: 20px auto;width: 640px;}
        .f_bold{font-weight: bold;}
        .f_16{font-size: 16px;}
        .width_100{width: 100%;}
        .margin_zero{margin:0}
        .padding-zero{padding: 0;}
        .service_area{box-shadow:0px 4px 4px rgb(0 0 0 / 6%);padding:15px;border:1px solid #ccc;border-radius: 6px;}
        .flex_flow{display: flex;}
        .align_center{align-items: center;}
        .city_list li{width:31%;margin: 0px 6px 20px;}
        .city_list{display: flex;list-style-type: none;margin:15px 0 0;padding:0;flex-flow: wrap;justify-content:flex-start;}
        .city_list li a{display: inline-block;border:1px solid #F27935;border-radius: 3px;color: #333;
            font-family: 'Nunito', sans-serif;text-decoration: none;padding: 6px 0px;width: 100%;
            box-sizing: border-box;text-align: center;background: #FFECE2;color: #F27935;    font-size: 12px;}
        .f_20{font-size: 20px;}
        .servicing_area{border-top: 1px solid rgb(204 204 204 / 50%);padding: 20px 0 0;}
        .servicble_area{margin:0;padding:0;list-style-type:none;font-family: 'Nunito';display: flex;justify-content: flex-start;flex-flow: wrap;}
        .servicble_area li{border: 1px solid #ccc;
            border-radius: 3px;
            padding: 8px 13px;
            font-size: 12px;width: 34%;
            margin: 15px 10px 0;}
        .active_bld{font-weight: bold;}
        .servicing_area{display: none;}

        @media(max-width:768px){

            .main_wrapper{width: 98%;}
            .service_area{box-sizing: border-box;}
            .city_list li{width:29%;}
            .f_20{font-size: 16px;}

        }




    </style>

</head>
<body>
<div class="main_wrapper">
    <div class="width_100 service_area">
        <h1 class="heading f_20 margin_zero flex_flow align_center"> <span style="margin-right: 10px;"><img src="{{asset('admin-theme/locatio_01.png')}}" width="18"/></span> <label>We are active in below cities:</label></h1>
        <ul class="city_list">

            @foreach($cities_arr as $key=>$val)
                <li>
                    @if($loop->iteration==1)
                        <a style="background-color:#F27935;color:#Fff!important;" class="all-cities" href="javascript:void(0)" onclick='$(".servicing_areaxx").hide();$(".all-cities").css("background-color","#FFECE2");$(".all-cities").css("color","#F27935");$("#{{str_replace(' ', '_', $key)}}").show();$(this).css("background-color","#F27935");$(this).css("color","#Fff");'>{{$key}}</a>
                    @else
                        <a class="all-cities" href="javascript:void(0)" onclick='$(".servicing_areaxx").hide();$(".all-cities").css("background-color","#FFECE2");$(".all-cities").css("color","#F27935");$("#{{str_replace(' ', '_', $key)}}").show();$(this).css("background-color","#F27935");$(this).css("color","#Fff");'>{{$key}}</a>
                    @endif
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


