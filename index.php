<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSV Geofence Parser To KML</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="card" style="width:50vw">
        <div class="card-header">
            Featured
        </div>
        <div class="card-body">
            <h5 class="card-title">Configuration</h5>
            <div class="row">
                <div class="col-md-6">
                    <label for="path" class="form-label">
                    Enter CSV File Name (Along with .csv extension)
                    </label>
                    <input type="text" id="path" name="path" class="form-control">
                </div>
            </div>
            <div class="row" style="">
                <div class="col-md-6">
                    <label for="radius" class="form-label">
                        Enter Radius (meters)
                    </label>
                    <input type="text" id="radius" name="radius" class="form-control">
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-md-6">
                    <button id="create" class="btn btn-primary">Create KML File</button>
                </div>
            </div>
        </div>
    </div>
</body>
<script>
    $(document).ready(function(){
        $("#create").on("click",function(){
            $.ajax({
                type: "GET",
                url: $("#path").val(),
                dataType: "text",
                success: function (data) {
                    processData(data);
                }
            });
        })
        function processData(data){
            var rows   = data.split('\n');
            var radius = $("#radius").val();
            var kml = `<?xml version="1.0" encoding="utf-8"?>
                        <kml>
                            <Document>
                                <name>
                                    Geofences
                                </name>`;
            $.each(rows, function (index, row) {
                var columns = row.split(',');
                var name      = columns[0];
                var latitude  = columns[1];
                var longitude = columns[2];
                latitude = parseFloat(latitude);
                longitude = parseFloat(longitude);
                var coords = (longitude+","+latitude+","+0)
                var string = `
                    <Placemark>
                    <name>
                        ${name}
                    </name>
                    <description color="9900ffff" width="${radius}">
                    </description>
                    <Point>
                        <coordinates>
                            ${coords}
                        </coordinates>
                    </Point>
                    </Placemark>`;
            
            kml+=string;
            });
            kml+=(`
                </Document>        
            </kml>`);
            var data 
            $.ajax({
                type: "POST",
                url: "createFile.php",
                data:{data:kml},
                success: function (data) {
                    if(data="Success"){
                        Swal.fire({
                        title: "Done",
                        icon: "success"
                        });
                    }
                }
            });
        }
    })
</script>
</html>