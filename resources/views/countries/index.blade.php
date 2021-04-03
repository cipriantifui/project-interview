@extends('layouts.app')

@section('content')
    <div class="container">

        <form id="form-zone-filter" action="{{ route('country.filter-zone') }}" method="POST"
              enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="form-group">
                <label for="zone-filter">Zone Filter</label>
                <select name="zoneFilter" id="zone-filter" class="form-control" style="width:150px">
                    <option value="">--- Choose ---</option>
                    @foreach ($countryZones as $zone)
                        <option value="{{ $zone }}">{{ $zone }}</option>
                    @endforeach
                </select>
            </div>
        </form>

        <div class="table">
            <div class="header-row">
                <div class="cell">Regiune <span id="sort-region" descendent="false"><i class="fa fa-sort"
                                                                                       aria-hidden="true"></i></span>
                </div>
                <div class="cell">Țară</div>
                <div class="cell">Limbă</div>
                <div class="cell">Monedă</div>
                <div class="cell">Latitudine</div>
                <div class="cell">Longitudine</div>
            </div>
            <div class="body-table" id="body-country-table">
                @foreach ($countries->country as $country)
                    <div class="row">
                        <div class="cell">{{ $country['zone'] }}</div>
                        <div class="cell">{{ $country->name }} <br> ({{$country->name['native']}})</div>
                        <div class="cell">{{ $country->language }} <br> ({{$country->language['native']}})</div>
                        <div class="cell">{{ $country->currency }} ({{$country->currency['code']}})</div>
                        <div class="cell">{{ $country->latitude }}</div>
                        <div class="cell">{{ $country->longitude }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        <p>Euro zone:
            @foreach($euroZoneCountries as $index => $countryName)
                {{ $index === (count($euroZoneCountries) - 1) ? $countryName : $countryName . ',' }}
            @endforeach
        </p>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            $('select#zone-filter').on('change', function () {
                var zone = $(this).val();
                $.ajax({
                    url: 'country/filter-zone',
                    type: "POST",
                    data: {zone: zone},
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    success: function (data) {
                        buildContentTable(data.countries);
                    }
                });

            });

            $('#sort-region').on('click', function () {
                var descendent = !$(this).data('descendent');
                $(this).data('descendent', descendent);

                $.ajax({
                    url: 'country/sort-zone',
                    type: "POST",
                    data: {descendent: descendent},
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    success: function (data) {
                        toggleSort(descendent);
                        buildContentTable(data.countries);
                    }
                });
            });

            function buildContentTable(countries) {
                let tableContent = '';
                $.each(countries, function (key, value) {
                    tableContent += '<div class="row">' +
                        '<div class="cell"> ' + value.zone + ' </div>' +
                        '<div class="cell"> ' + value.name.name + ' <br> (' + value.name.native + ') </div>' +
                        '<div class="cell"> ' + value.language.language + ' <br> (' + value.language.native + ') </div>' +
                        '<div class="cell"> ' + value.currency.currency + ' <br> (' + value.currency.code + ') </div>' +
                        '<div class="cell"> ' + value.latitude + ' </div>' +
                        '<div class="cell"> ' + value.longitude + ' </div>' +
                        '</div>';
                });
                $('#body-country-table').html(tableContent);
            }

            function toggleSort(descendent) {
                let element = $('#sort-region');
                if (descendent) {
                    element.find('i').addClass('fa-sort-down');
                    element.find('i').removeClass('fa-sort');
                    element.find('i').removeClass('fa-sort-up');
                } else {
                    element.find('i').addClass('fa-sort-up');
                    element.find('i').removeClass('fa-sort');
                    element.find('i').removeClass('fa-sort-down');
                }
            }
        });
    </script>
@endsection
