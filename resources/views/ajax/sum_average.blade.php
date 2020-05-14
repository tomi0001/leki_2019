Ilość dni {{$sumDay}}
    @for ($i=0;$i < count($hourDrugs);$i++)
    <div class="ajax_succes" >
    {{$hourDrugs[$i][2]}} - {{$hourDrugs[$i][1]}}<br>
    dawka {{$hourDrugs[$i][0]}}<br>

    ilość dni {{$arrayDay[$i]}}<br>
        Ilośc wzięć {{$hourDrugs[$i][4]}}
    </div>
    @if ($hourDrugs[$i][3] == 1)
    <hr>
    @endif
    <br>
    @endfor
    
