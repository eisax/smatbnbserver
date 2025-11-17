@php

$lang = Session::get('language');

@endphp
@extends('layouts.main')

@section('title')
    {{ __('Home') }}
@endsection
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.css">
<style>
 /* Clean, shadow-free dashboard */
 .card { box-shadow: none !important; border: 1px solid #e9ecef; border-radius: 10px; }
 .card-body { padding: 1rem 1.25rem; }
 .card-hrader, .card-header { border-bottom: 1px solid #f1f3f5; background: transparent; }
 .curtain { display: none !important; }
 .svg_icon { opacity: 0.9; }
 .total_number { font-weight: 700; font-size: 1.75rem; }
 .card_title { color: #6c757d; font-weight: 500; margin-top: .25rem; }
 .section { padding-top: 0; }
 .proeprty_chart { border: 1px solid #e9ecef; }
 .tabs .tab-li__link { border: none; background: transparent; color: #495057; }
 .tabs .tab-li__link.active { color: #087c7c; }
 /* New stat tiles */
 .stat-card { display:flex; align-items:center; gap:.75rem; padding:1rem 1.25rem; }
 .stat-card .icon { width:40px; height:40px; display:flex; align-items:center; justify-content:center; border-radius:10px; background:#ffe3ec; color:#d6336c; }
 /* Hide legacy bulky stat grid (left column in the second row) */
 .section > .row:nth-of-type(2) > .col-md-4 { display:none; }
</style>

<section class="section">
    <div class="dashboard_title mb-3"> {{ __('Hi, Admin') }}</div>
    <!-- Minimalist stat tiles -->
    <div class="row g-3 mb-3">
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card h-100">
                <div class="stat-card">
                    <div class="icon"><i class="bi bi-people"></i></div>
                    <div>
                        <div class="value">{{ $list['total_customer'] }}</div>
                        <div class="label">{{ __('Total Customers') }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card h-100">
                <div class="stat-card">
                    <div class="icon"><i class="bi bi-houses"></i></div>
                    <div>
                        <div class="value">{{ $list['total_properties'] }}</div>
                        <div class="label">{{ __('Total Properties') }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card h-100">
                <div class="stat-card">
                    <div class="icon"><i class="bi bi-house-door"></i></div>
                    <div>
                        <div class="value">{{ $list['total_sell_property'] }}</div>
                        <div class="label">{{ __('Properties for Sale') }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card h-100">
                <div class="stat-card">
                    <div class="icon"><i class="bi bi-key"></i></div>
                    <div>
                        <div class="value">{{ $list['total_rant_property'] }}</div>
                        <div class="label">{{ __('Properties for Rent') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
   
    <div class="row">
        <div class="card">
            <div class="card-header">
                <div class="recent_list_heading">
                    {{ __('Recent Properties') }}
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div id="toolbar">
                        {{-- Filter Status --}}
                        <div class="col-12">
                            <select id="recent-property-type-filter" class="form-select form-control-sm">
                                <option value="">{{ __('Select Property Type') }} </option>
                                <option value="0">{{ __('Sell') }}</option>
                                <option value="1">{{ __('Rent') }}</option>
                                <option value="2">{{ __('Sold') }}</option>
                                <option value="3">{{ __('Rented') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12">
                        <table class="table table-striped mb-0" aria-describedby="mydesc"
                            id="table_list" data-toggle="table" data-url="{{ url('getPropertyList') }}"
                            data-click-to-select="true" data-side-pagination="server"
                            data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-search-align="right"
                            data-toolbar="#toolbar" data-show-columns="true" data-show-refresh="true"
                            data-trim-on-search="false" data-responsive="true" data-sort-name="id" data-sort-order="desc"
                            data-pagination-successively-size="3" data-query-params="recentPropertiesQueryParams">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col" data-field="id" data-sortable="true"> {{ __('ID') }}</th>
                                    <th scope="col" data-field="category.category" data-align="center" data-sortable="false"> {{ __('Category') }}</th>
                                    <th scope="col" data-field="Property_name" data-align="center" data-sortable="false"> {{ __('Property Name') }}</th>
                                    <th scope="col" data-field="city" data-align="center" data-sortable="false"> {{ __('Property City') }}</th>
                                    <th scope="col" data-field="added_by" data-align="center" data-sortable="false"> {{ __('Added By') }} </th>
                                    <th scope="col" data-field="propery_type" data-formatter="propertyTypeFormatter" data-align="center" data-sortable="true"> {{ __('Type') }}</th>
                                    <th scope="col" data-field="price" data-align="center" data-sortable="false">{{ __('Price') }} </th>
                                    @if (has_permissions('delete', 'property'))
                                        <th scope="col" data-field="only_delete_operate" data-align="center" data-sortable="false"> {{ __('Action') }}</th>
                                    @endif
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" name="currency_symbol" value="{{ system_setting('currency_symbol') }}" id="currency_symbol">
    <input type="hidden" name="map_data" id="map_data" value="{{ $properties }}">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="card map_title h-100">
                <div class="card-header border-0 pb-0">
                    <h3 style="font-weight: 600">{{ __('Most Viewed') }}</h3>
                </div>
                <div class="card-body h-50">
                    <div id="world-map" style="width: 100%; height:400px"></div>
                </div>
            </div>
        </div>

    </div>
    <div class="row mt-5 mb-10">
        <div class="col-md-6 col-sm-12">
            <div class="card h-100">
                <div class="card-header">
                    <div class="recent_list_heading">{{ __('Total Categories') }}</div>
                </div>
                <div class="card-body mt-5">
                    <div id="pie_chart"></div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-12">
            <div class="card h-100">
                <div class="card-header">
                    <div class="recent_list_heading">{{ __('Featured Properties') }}</div>
                </div>
                <div class="card-body">
                    <table class="table table-striped mb-0" aria-describedby="mydesc"
                        id="table_list_featured_property" data-toggle="table" data-url="{{ url('getFeaturedPropertyList') }}"
                        data-click-to-select="true" data-side-pagination="server"
                        data-page-list="[5, 10, 20, 50, 100, 200, All]" data-search-align="right"
                        data-trim-on-search="false" data-responsive="true" data-sort-name="id" data-sort-order="desc"
                        data-pagination-successively-size="3">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col" data-field="id" data-sortable="false"> {{ __('ID') }}</th>
                                <th scope="col" data-field="title" data-align="left" data-sortable="false" data-formatter="featuredPropertiesDataFormatter"> {{ __('Property') }}</th>
                                <th scope="col" data-field="status" data-sortable="false" data-align="center" data-width="5%" data-formatter="enableDisableSwitchFormatter"> {{ __('Enable/Disable') }}</th>
                                @if (has_permissions('update', 'property'))
                                    <th scope="col" data-field="operate" data-align="center" data-sortable="false"> {{ __('Action') }}</th>
                                @endif
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>


</section>
@endsection
@section('script')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>




<script>
    $("#recent-property-type-filter").on('change', function() {
        $('#table_list').bootstrapTable('refresh');
    });

    function recentPropertiesQueryParams(p) {
        return {
            sort: p.sort,
            order: p.order,
            offset: p.offset,
            search: p.search,
            limit: 10,
            status: 1,
            property_type: $("#recent-property-type-filter").val(),
        };
    }

        var options = {
                series:[{{ implode(',', array_values($category_count)) }}],
                chart: {
                     type: 'donut',
                     height:"700px"
                },
                labels:[{!! implode(',', ($category_name)) !!}],
                  plotOptions: {
                },

                responsive: [{
                     breakpoint: 480,
                     options: {
                        chart: {
                          width:50,
                          height:20
                          },
                }}],
                legend: {
                    show: true,
                    showForSingleSeries: false,
                    showForNullSeries: true,
                    showForZeroSeries: true,
                    position: 'bottom',
                    horizontalAlign: 'center',
                    fontSize: '18px',
                    fontFamily: 'Helvetica, Arial',
                    fontWeight: 400,
                    itemMargin: {
                        horizontal:30,
                        vertical: 10
                    }
                }

        };

        var chart1 = new ApexCharts(document.querySelector("#pie_chart"), options);
        chart1.render();
        var myArray =<?php echo json_encode($chartData); ?>;
            const data = {
                monthTab: {
                    series1: [{{ implode(',', array_values($chartData['sellmonthSeries'])) }}],
                    series2: [{{ implode(',', array_values($chartData['rentmonthSeries'])) }}],
                    categories: [{!! implode(',', $chartData['monthDates']) !!}],

                },
                weekTab: {
                    series1:[{{ implode(',', array_values($chartData['sellweekSeries'])) }}],
                    series2: [{{ implode(',', array_values($chartData['rentweekSeries'])) }}],
                    categories: [{!! implode(',', $chartData['weekDates']) !!}],

                },
                dailyTab: {
                    series1: [{{ implode(',', array_values($chartData['sellcountForCurrentDay'])) }}],
                    series2: [{{ implode(',', array_values($chartData['rentcountForCurrentDay'])) }}],
                    categories: [{!! implode(',', $chartData['currentDates']) !!}],
                },
            };
            var chartData = data['monthTab'];
            var options = {
                series: [
                        { name: window.trans['Rent'], data: chartData.series2 },
                        { name: window.trans['Sell'], data: chartData.series1 },
                ],
                chart: {
                    height: 350,
                    type: 'area',
                    zoom: {
                        enabled: false // Disable zooming
                    },
                    toolbar: {
                        show: false // Hide the toolbar (including download button)
                    }
                },
                dataLabels: {
                enabled: false
                },
                stroke: {
                    curve: 'smooth'
                },
                xaxis: {
                    type: 'date',
                    categories:chartData.categories,
                },
                tooltip: {
                    x: {
                        format: 'dd/MM/yy HH:mm'
                    },
                },
                colors: ['#EB9D55', '#47BC78'],
         };
        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();

        $(document).ready(function(){

            $('#tab1').attr('class','active');
            chart.render();
        });

        var nestedTabSelect = (tabsElement, currentElement) => {
        const tabs = tabsElement ?? 'ul.tabs';
        const currentClass = currentElement ?? 'active';

        document.querySelectorAll(tabs).forEach(function (tabContainer) {
            let activeLink, activeContent;
            const links = Array.from(tabContainer.querySelectorAll("a"));

            activeLink = links.find(function (link) {
                return link.getAttribute("href") === location.hash;
            }) || links[0];
            activeLink.classList.add(currentClass);

            activeContent = document.querySelector(activeLink.getAttribute("href"));
            activeContent.classList.add(currentClass);

            links.forEach(function (link) {
                if (link !== activeLink) {
                    const content = document.querySelector(link.getAttribute("href"));
                    content.classList.remove(currentClass);
                }
            });

            tabContainer.addEventListener("click", function (e) {
                if (e.target.tagName === "A") {
                    tab = e.target.id;
                    chartData = data[tab];
                    chart.updateOptions({
                        series: [
                            { name: window.trans['Rent'] ?? 'Rent', data: chartData.series2 },
                            { name: window.trans['Sell'] ?? 'Sell', data: chartData.series1 },
                        ],
                        xaxis: {
                            categories: chartData.categories
                        }

                    });



                    // chart.render();
                    // Make the old tab inactive.
                    activeLink.classList.remove(currentClass);
                    activeContent.classList.remove(currentClass);

                    // Update the variables with the new link and content.
                    activeLink = e.target;
                    activeContent = document.querySelector(activeLink.getAttribute("href"));

                    // Make the tab active.
                    activeLink.classList.add(currentClass);
                    activeContent.classList.add(currentClass);

                    e.preventDefault();
                }
            });
        });
    };

    nestedTabSelect('ul.tabs', 'active');
    var mapData = JSON.parse($('#map_data').val());

    var currency_symbol = $('#currency_symbol').val();
    var markerValues = mapData.map(function (property, index) {
        var featured = "";
        if(property.promoted == true){
            featured = `<div class="text-overlay-top">${window.trans['Featured']}</div>`;
        }
        let propertyTypeText = property.property_type;
        return {
            latLng: [parseFloat(property.latitude), parseFloat(property.longitude)],
            name: property.city,
            label: property.city,
            style: {
                fill: '#087C7C',
                stroke: '#00000'
            },
            card: {
                content: '<div class="card_map">' +
                    '<div class="image-container">' +
                    '<img src="' + property.title_image + '" alt="Your Image" height="100%" width="100%">' +
                    `${featured}<div class="text-overlay-bottom">${window.trans[propertyTypeText]}</div>` +
                    '</div>' +
                    '<div>' +
                    '<div class="d-flex">' +
                    '<img src="' + property.category.image + '" alt="Your Image" height="24px" width="24px">' +
                    '<div class="category">' + property.category.category + '</div>' +

                    '</div>' +
                    '<div class="title mt-3">' + property.title + '</div>' +
                    '<div class="price mt-2">' + currency_symbol + property.price + '</div>' +
                    '<div class="city">' +
                    '<i class="bi bi-geo-alt"></i>' + property.city +

                    '</div>' +

                    '</div>'


            }
        };
    });

    $('#world-map').vectorMap({
        map: 'world_mill',
        // scaleColors: ['#116D6E', '#116D6E'],
        backgroundColor: '#fffff',

        markerStyle: {
            initial: {
                strokeWidth: 1,
                stroke: '#383f47',
                fillOpacity: 1,
                r: 8,

            },
            onMarkerLabelShow: function (event, label, index) {
                // Add custom CSS classes to the label element
                label.addClass('custom-marker-label');
            },

        },

        markers: markerValues,
        series: {
            markers: [{
                // attribute: 'fill',
                scale: {}, // Empty scale object to be populated dynamically
                values: mapData.map(function (property) {
                    return property.city;
                })
            }]
        },
        onMarkerTipShow: function (event, label, index) {
            var cardContent = markerValues[index].card.content;
            label.html(cardContent);
        }
    });
</script>
@endsection
