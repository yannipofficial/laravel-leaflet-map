@extends('layouts.app')

@section('content')
    <div class="add-point-alert alert alert-warning position-fixed w-100" role="alert" id="addModeAlert">
        <span>Click anywhere in the map to add a new place!</span>
        <span>Click <a href="#" onclick="toggleAddMode(0);">here</a> to cancel the action.</span>
    </div>

    <div id="categoriesSelector">
        <button type="button" id="catBtnAll" data-category="all" class="btn btn-dark rounded-pill btn-sm m-1 active"
            onclick="toggleCategory('all');">
            <i class="bi-circle-fill" style="color:#FFFFFF"></i> All Categories
        </button>

        @foreach ($categories as $category)
            <button type="button" id="catBtn{{ $category->id }}" data-category="{{ $category->id }}"
                class="btn btn-dark rounded-pill btn-sm m-1" onclick="toggleCategory({{ $category->id }});">
                <i class="bi-circle-fill" style="color:#{{ $category->color }}"></i> {{ $category->name }}
            </button>
        @endforeach

    </div>
    <div class="card col-md-4" id="controlPanel" style="display:none;">
        <div class="card-header cardLabel d-flex justify-content-between">
            <div>Modifying #<span id="placeIDlabel">1s</span></div>
            <div id="hidePanelBtn" style="cursor:pointer;" onClick="hidePanel();">X</div>
        </div>

        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
            <div id="loadingIcon">
                <center><img src="/storage/img/loading.svg"></center>
            </div>
            <div id="cardContents">
                <div id="placeDetails">
                    <form>
                        <div class="form-group">
                            <label for="nameInput">Name</label>
                            <input type="text" class="form-control" id="nameInput">
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col">
                                    <label for="lngInput">Longitude</label>
                                    <input type="text" class="form-control" id="lngInput" disabled>
                                </div>
                                <div class="col">
                                    <label for="latInput">Latitude</label>
                                    <input type="text" class="form-control" id="latInput" disabled>
                                </div>
                            </div>

                        </div>
                        <div class="form-group">
                            <label for="categorySelect">Category</label>
                            <select class="form-select" id="categorySelect">
                                @foreach ($categories as $category)
                                    <option class="cat{{ $category->id }}" value="{{ $category->id }}">{{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="descriptionInput">Description</label>
                            <textarea class="form-control" id="descriptionInput" rows="3"></textarea>
                        </div>
                        <div class="form-group pt-2 float-end">
                            <button id="deleteBtn" type="button" class="btn btn-sm btn-danger">
                                Delete Place
                            </button>
                            <button id="modifyBtn" type="button" class="btn btn-sm btn-warning">
                                Modify Place
                            </button>
                        </div>
                    </form>

                    <button type="button" id="likeBtn"
                        class="btn btn-sm btn-danger d-flex justify-content-center align-items-center mt-1">
                        <i class="bi-heart-fill pt-1" style="font-size:16px;" id="likeHeart"></i>
                        <span class="ms-1" id="likeCounter">0</span>
                    </button>
                    <div class="form-group float-end">
                        Posted by <b id="postedUsername">___</b>
                    </div>
                </div><br>
                <h5 class="place-popup-subtitle">
                    Photos
                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal"
                        data-bs-target="#newPhotoModal">Add a photo</button>
                </h5>
                <template id="photoTemplate">
                    <div id="photoContainer" class="d-inline-block border rounded border-secondary m-1" width="93"
                        height="150" style="cursor:pointer;">
                        <img src="" id="photoElement"
                            onclick="showPhotoModal($(this).attr('data-id'),$(this).attr('data-src'),$(this).attr('data-uid-name'),$(this).attr('data-uid'));">
                    </div>
                </template>

                <div class="mb-4" id="photosList"></div>

                <h5 class="place-popup-subtitle">
                    Comments
                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal"
                        data-bs-target="#newCommentModal">Add a comment</button>
                </h5>
                <template id="commentTemplate">
                    <div class="card mt-2" id="commentContainer">
                        <div class="card-body">
                            <div class="d-flex w-100 align-items-center justify-content-between">
                                <h5 class="card-title" id="usernameElement">amin</h5>
                                <div class="toolbox">
                                    <span class="material-symbols-outlined text-danger" id="commentDeleteBtn"
                                        style="cursor:pointer; display:none;"
                                        onClick="deleteComment($(this).attr('data-id'))">
                                        delete
                                    </span>
                                </div>
                            </div>

                            <h7 class="card-subtitle mb-2 text-muted d-block" id="dateElement">2022-05-01 11:30:25</h7>
                            <p class="card-text" id="commentBodyElement">comment</p>
                        </div>
                    </div>
                </template>
                <div id="commentList">

                </div>
                <div class="paginationControl mt-2 d-flex justify-content-center">
                    <button type="button" class="btn btn-dark btn-sm" id="loadMoreBtn" style="display:none;">Load
                        More</button>
                </div>
            </div>



        </div>
    </div>
    <div id="map"></div>
    <div class="modal fade" id="newCommentModal" tabindex="-1" aria-labelledby="commentLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered  modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="commentLabel">Create new comment</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="commentInput">Your Comment:</label>
                            <textarea class="form-control" id="commentInput" rows="3"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button id="createCommentBtn" type="button" class="btn btn-primary">Post</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="newPhotoModal" tabindex="-1" aria-labelledby="photoLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered  modal-dialog-scrollable">
            <div class="modal-content">
                <form method="POST" id="upload-image-form">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="photoLabel">Add a new photo</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <!-- {{ csrf_field() }} -->
                        <p>
                            Upload Image: <input type="file" class="form-control" id="photoInput" name="photo" />
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" name="submit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="showPhotoModal" tabindex="-1" aria-labelledby="showPhotoLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered  modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="showPhotoLabel">Photo #</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img src="" id="photoModalElement" style="max-width:100%; max-height:100%">

                </div>
                <div class="modal-footer">
                    <h4 id="madeByPhoto">Uploaded by: </h4>
                    <span class="material-symbols-outlined text-danger" id="photoDeleteBtn"
                        style="cursor:pointer; display:block;" onClick="deletePhoto($(this).attr('data-id'))">
                        delete
                    </span>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div>
    <div class="modal fade" id="newPlaceModal" tabindex="-1" aria-labelledby="newPlaceLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered  modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="newPlaceLabel">Create new place</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="nameInputNew">Name</label>
                            <input type="text" class="form-control" id="nameInputNew">
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col">
                                    <label for="lngInputNew">Longitude</label>
                                    <input type="text" class="form-control" id="lngInputNew">
                                </div>
                                <div class="col">
                                    <label for="latInputNew">Latitude</label>
                                    <input type="text" class="form-control" id="latInputNew">
                                </div>
                            </div>

                        </div>
                        <div class="form-group">
                            <label for="categorySelectNew">Category</label>
                            <select class="form-select" id="categorySelectNew">
                                @foreach ($categories as $category)
                                    <option class="cat{{ $category->id }}" value="{{ $category->id }}">
                                        {{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="descriptionInputNew">Description</label>
                            <textarea class="form-control" id="descriptionInputNew" rows="3"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button id="createBtn" type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                latit = position.coords.latitude;
                longit = position.coords.longitude;
                map.setView([latit, longit], 13);
            })
        }


        var isTyping = 0;
        var liked = 0;



        window.addEventListener('DOMContentLoaded', (event) => {
            const scrollContainer = document.querySelector("#categoriesSelector");
            scrollContainer.addEventListener("wheel", (evt) => {
                evt.preventDefault();
                scrollContainer.scrollLeft += evt.deltaY;
            });

            document.getElementById("likeBtn").addEventListener("click", function() {
                const id = document.getElementById("placeIDlabel").innerText;
                var likeCounter = document.getElementById("likeCounter").innerText;
                if (liked == 0) {
                    likeCounter++;
                    toggleLike(1);
                    postLike(id);
                } else {
                    likeCounter--;
                    toggleLike(0);
                    deleteLike(id);
                }
                document.getElementById("likeCounter").innerText = likeCounter;
            });

            document.getElementById("modifyBtn").addEventListener("click", function() {
                const id = document.getElementById("placeIDlabel").innerText;
                updatePlace(id);
            })

            document.getElementById("deleteBtn").addEventListener("click", function() {
                const id = document.getElementById("placeIDlabel").innerText;
                deletePlace(id);
            })

            document.getElementById("createBtn").addEventListener("click", function() {
                createPlace();
            })

            document.getElementById("createCommentBtn").addEventListener("click", function() {
                const id = document.getElementById("placeIDlabel").innerText;
                createComment(id);
            })


        });



        var addToggled = false;
        var searchingList = [];
        var map = L.map('map').setView([38.44, 22.50], 13);
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            minZoom: 3,
            noWrap: true,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
            zoomControl: false,
        }).addTo(map);
        map.zoomControl.remove();
        L.control.zoom({
            position: 'bottomright'
        }).addTo(map);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });


        $('#upload-image-form').submit(function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            let postsID = (document.getElementById('placeIDlabel').innerText);
            formData.append('postID', postsID);
            $.ajax({
                type: "POST",
                url: '/api/image/upload',
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) {
                    console.log(data);
                    location.reload();
                }
            })
        });

        function toggleAddMode(choice) {
            if (choice == 1) {
                addToggled = true;
                document.getElementById('addModeAlert').style.display = 'block';
            } else {
                addToggled = false;
                document.getElementById('addModeAlert').style.display = 'none';
            }

        }

        function toggleCategory(cat) {
            for (i = 0; i < markersCategories.length; i++) {
                map.removeLayer(window[markersCategories[i]]);
            }
            if (cat == "all") {
                for (i = 0; i < markersCategories.length; i++) {
                    map.addLayer(window[markersCategories[i]]);
                }
            } else {
                map.addLayer(window['markersCat' + cat]);
            }

            const allBtns = document.querySelectorAll('#categoriesSelector button');
            const activeBtn = document.querySelector('#categoriesSelector button[data-category="' + cat + '"]');

            allBtns && allBtns.forEach(btn => {
                btn.classList.remove('active');
            })

            activeBtn && activeBtn.classList.add('active');
        }

        map.on('click', function(e) {
            if (addToggled == true) {
                marker = new L.Marker(e.latlng).addTo(map);

                toggleNewPlaceModal(e.latlng.lat, e.latlng.lng);
            }
        })

        function toggleNewPlaceModal(lat, long) {
            toggleAddMode(0);
            $('#newPlaceModal').modal('toggle');
            document.getElementById('lngInputNew').value = long;
            document.getElementById('latInputNew').value = lat;

            console.log(lat, long);
        }

        document.getElementById('searchInput').addEventListener("input", function() {
            isTyping = 1;
            var searchQuery = document.getElementById('searchInput').value;
            doSearch(1, 0);

            function doSearch(page, doAddition) {
                $.get('/api/search' + '?' + 'search=' + searchQuery + '&page=' + page).then(
                    function(res) {
                        console.log(res);
                        if (doAddition == 0) {
                            document.getElementById('searchList').innerHTML = "";
                            document.getElementById('searchBarLoadMore').style.display = "none";
                            document.getElementById('searchSortedBy').style.display = "none";
                        }
                        searchingList = [];
                        if (res[0] != null) {
                            for (i = 0; i < (res[0].length); i++) {
                                searchingList[i] = [];
                                const searchList = document.querySelector("#searchList");
                                const searchItem = document.querySelector('#searchItemTemp');
                                const clone = searchItem.content.cloneNode(true);
                                clone.querySelector("#searchItemTitle").innerText = res[0][i]['name'];
                                clone.querySelector("#searchItemLat").innerText = res[0][i]['lat'];
                                clone.querySelector("#searchItem").setAttribute('id', 'dLatLng' + i);
                                clone.querySelector("#searchItemLikesCounter").innerText = res[0][i][
                                    'likes'
                                ];
                                searchingList[i][0] = res[0][i]['lat'];
                                clone.querySelector('#searchItemLng').innerText = res[0][i]['long'];
                                searchingList[i][1] = res[0][i]['long'];
                                searchList.appendChild(clone);
                            };
                            document.getElementById('searchSortedBy').style.display = "block";
                            document.getElementById('searchSortedBy').innerText =
                                "Sorted By Likes (Results:" +
                                res[1][0] + ")";
                            if (res[1][2] == res[1][1]) {
                                document.getElementById('searchBarLoadMore').style.display = "none";
                            } else {
                                document.getElementById('searchBarLoadMore').style.display = "block";
                                var old_element = document.getElementById("searchBarLoadMore");
                                var new_element = old_element.cloneNode(true);
                                old_element.parentNode.replaceChild(new_element, old_element);
                                document.getElementById('searchBarLoadMore').addEventListener("click",
                                    function() {
                                        doSearch(((res[1][2]) + 1), 1);
                                    });
                            }

                        }

                        isTyping = 0;
                    });
            }
        })


        function panToPlace(latlng) {
            var desiredLatLngPos = latlng.charAt(latlng.length - 1);
            map.panTo(new L.LatLng(searchingList[desiredLatLngPos][0], searchingList[desiredLatLngPos][1]));
        }

        function hidePanel() {
            document.getElementById('controlPanel').style.display = "none";
        }

        function displayData(data) {
            map.panTo(new L.LatLng(data['lat'], data['long']));
            console.log(data);
            window.history.pushState('place', data['name'], '/place/' + data['id']);
            document.getElementById('commentList').innerHTML = "";
            document.getElementById('photosList').innerHTML = "";
            document.getElementById('controlPanel').style.display = "block";
            document.getElementById('placeIDlabel').innerText = data['id'];
            document.getElementById('nameInput').value = data['name'];
            document.getElementById('categorySelect').value = data['cat'];
            console.log(data['cat'])
            document.getElementById('likeCounter').innerText = data['likes'];
            document.getElementById('lngInput').value = data['long'];
            document.getElementById('latInput').value = data['lat'];
            document.getElementById('postedUsername').innerText = data['user'].name;
            document.getElementById('descriptionInput').value = data['description'];
            if (({{ Auth::user()->id }} == data['userID']) || {{ Auth::user()->isAdmin() ? 'true' : 'false' }} === true) {
                document.getElementById('deleteBtn').style.display = "inline-block";
                document.getElementById('modifyBtn').style.display = "inline-block";
            } else {
                document.getElementById('deleteBtn').style.display = "none";
                document.getElementById('modifyBtn').style.display = "none";
            }
            getLike(data['id']);
            loadPhotos(data['id']);
            getComments(data['id'], 1);
            document.getElementById('loadingIcon').style.display = 'none';
            document.getElementById('cardContents').style.display = 'block';

        }

        function getLike(id) {

            $.ajax({
                type: "GET",
                url: '/api/likes/' + id,
                success: function(data) {
                    console.log(data);
                    if (data == 1) {
                        toggleLike(1);
                    } else {
                        toggleLike(0);
                    }
                }
            })
        }

        function postLike(id) {

            $.ajax({
                type: "POST",
                url: '/api/likes/' + id,
                success: function(data) {
                    console.log(data);

                }
            })
        }

        function deleteLike(id) {

            $.ajax({
                type: "DELETE",
                url: '/api/likes/' + id,
                success: function(data) {
                    console.log(data);

                }
            })
        }



        function toggleLike(state) {
            if (state == 1) {
                liked = 1;
                document.getElementById('likeBtn').classList.add("active");
                document.getElementById('likeHeart').classList.remove("bi-heart");
                document.getElementById('likeHeart').classList.add("bi-heart-fill");
            } else {
                liked = 0;
                document.getElementById('likeBtn').classList.remove("active");
                document.getElementById('likeHeart').classList.remove("bi-heart-fill");
                document.getElementById('likeHeart').classList.add("bi-heart");
            }
        }

        function getComments(id, page) {
            $.ajax({
                type: "GET",
                url: '/api/comments/' + id + '?page=' + page,
                success: function(data) {
                    if (data.data.length != 0) {
                        var i = 0;
                        data.data.forEach(function() {
                            const commentList = document.querySelector("#commentList");
                            const template = document.querySelector('#commentTemplate');
                            const clone = template.content.cloneNode(true);
                            clone.querySelector("#usernameElement").innerText = data.data[i]['user']
                                .name;
                            clone.querySelector("#commentContainer").id = 'comment' + data.data[i][
                                'id'
                            ];
                            clone.querySelector("#dateElement").innerText = (new Date(data.data[i][
                                'created_at'
                            ])).toLocaleString();

                            if (({{ Auth::user()->id }} == data.data[i]['userID']) ||
                                {{ Auth::user()->isAdmin() ? 'true' : 'false' }} === true) {
                                clone.querySelector("#commentDeleteBtn").style.display = "inline-block";
                            }
                            clone.querySelector("#commentDeleteBtn").setAttribute('data-id', data.data[
                                i]['id']);
                            clone.querySelector("#commentBodyElement").innerText = data.data[i][
                                'commentBody'
                            ];
                            commentList.appendChild(clone);
                            i = i + 1;
                        });

                        $('#loadMoreBtn').replaceWith($('#loadMoreBtn').clone());
                        if (data.last_page == "1" && data.current_page == "1") {
                            document.getElementById("loadMoreBtn").style.display = "none";
                        } else if (data.last_page == data.current_page) {
                            document.getElementById("loadMoreBtn").style.display = "none";
                        } else {
                            document.getElementById("loadMoreBtn").style.display = "block";
                            document.getElementById("loadMoreBtn").addEventListener("click", function() {
                                getComments(id, ((data.current_page) + 1))
                            });
                        }
                    } else {
                        document.getElementById('commentList').innerHTML = "<center>No Comments</center>";
                    }


                }
            })
        }

        var photosArray = [];

        function loadPhotos(id) {
            $.ajax({
                type: "GET",
                url: '/api/image?placeID=' + id,
                success: function(data) {
                    if (data.length != 0) {
                        photosArray = [];
                        console.log(data);
                        for (i = 0; i < data.length; i++) {
                            photosArray[i] = [];
                            photosArray[i]['ID'] = data[i]['id'];
                            photosArray[i]['UIDName'] = data[i]['name'];
                            photosArray[i]['UID'] = data[i]['userID'];
                            photosArray[i]['longURL'] = data[i]['imgPath'];
                            photosArray[i]['shortURL'] = data[i]['imgPathSmall'];

                            const photosList = document.querySelector("#photosList");
                            const photoTemplate = document.querySelector('#photoTemplate');
                            const photoClone = photoTemplate.content.cloneNode(true);
                            photoClone.querySelector("#photoElement").src = "/storage/photos/thumbnail/" +
                                photosArray[i]['shortURL'];
                            photoClone.querySelector("#photoElement").setAttribute('data-id', photosArray[i][
                                'ID'
                            ]);
                            photoClone.querySelector("#photoElement").setAttribute('data-src', photosArray[i][
                                'longURL'
                            ]);
                            photoClone.querySelector("#photoElement").setAttribute('data-uid-name', photosArray[
                                i][
                                'UIDName'
                            ]);
                            photoClone.querySelector("#photoElement").setAttribute('data-uid', photosArray[i][
                                'UID'
                            ]);
                            console.log(photosArray[i]['longURL']);

                            photosList.appendChild(photoClone);
                        }
                        console.log(photosArray);
                    } else {
                        document.getElementById('photosList').innerHTML = "<center>No Photos</center>";
                    }
                }
            });
        }

        function showPhotoModal(id, imageURL, userIDName, userID) {
            console.log(photosArray);
            document.getElementById('photoModalElement').src = "/storage/photos/" + imageURL;
            document.getElementById('showPhotoLabel').innerText = "Photo #" + id;
            document.getElementById('madeByPhoto').innerText = "Uploaded By " + userIDName;
            document.getElementById("photoDeleteBtn").setAttribute('data-id', id);
            if (({{ Auth::user()->id }} == userID) || {{ Auth::user()->isAdmin() ? 'true' : 'false' }} === true) {
                document.getElementById("photoDeleteBtn").style.display = "block";
            } else {
                document.getElementById("photoDeleteBtn").style.display = "none";
            }
            $('#showPhotoModal').modal('toggle');
        }

        @if (isset($id))
            getPlace({{ $id }});
        @endif


        var markers = L.markerClusterGroup({
            disableClusteringAtZoom: 18,
            spiderfyOnMaxZoom: true,
            chunkedLoading: true
        });

        var markersCategories = [];
        @foreach ($categories as $category)
            markersCat{{ $category->id }} = L.featureGroup.subGroup(markers);
            markersCategories.push('markersCat{{ $category->id }}');
        @endforeach

        @foreach ($places as $place)
            markersCat{{ $place->cat }}.addLayer(L.marker([{{ $place->lat }}, {{ $place->long }}]).on(
                'click',
                function(e) {
                    getPlace({{ $place->id }});
                }));
        @endforeach

        markers.addTo(map);
        @foreach ($categories as $category)
            markersCat{{ $category->id }}.addTo(map);
        @endforeach

        function getPlace(id) {
            document.getElementById('loadingIcon').style.display = 'block';
            document.getElementById('cardContents').style.display = 'none';

            console.log(id);
            $.ajax({
                type: "GET",
                url: '/api/place/' + id,
                success: function(data) {
                    console.log(data);
                    if (data.length != 0) {
                        displayData(data);
                    } else {
                        console.log('not found');
                    }



                }
            })
        }

        function updatePlace(id) {
            const nameInput = document.getElementById('nameInput').value;
            const categorySelect = document.getElementById('categorySelect').value;
            const descriptionInput = document.getElementById('descriptionInput').value;


            console.log(id);
            $.ajax({
                type: "PUT",
                url: '/api/place/' + id,
                data: {
                    name: nameInput,
                    category: categorySelect,
                    description: descriptionInput
                },
                success: function(data) {
                    console.log(data);
                    if (data == "401") {
                        console.log('Unauthorized');
                    } else {

                    }
                }
            });

        }

        function createPlace() {
            const nameInputNew = document.getElementById('nameInputNew').value;
            const categorySelectNew = document.getElementById('categorySelectNew').value;
            const lngInputNew = document.getElementById('lngInputNew').value;
            const latInputNew = document.getElementById('latInputNew').value;
            const descriptionInputNew = document.getElementById('descriptionInputNew').value;


            $.ajax({
                type: "POST",
                url: '/api/place/',
                data: {
                    name: nameInputNew,
                    category: categorySelectNew,
                    latitude: latInputNew,
                    longitude: lngInputNew,
                    description: descriptionInputNew
                },
                success: function(data) {
                    console.log(data);
                    window.location.href = '/place/' + data;
                }
            });

        }

        function createComment(id) {
            const commentInput = document.getElementById('commentInput').value;


            $.ajax({
                type: "POST",
                url: '/api/comments/' + id,
                data: {
                    commentBody: commentInput,
                },
                success: function(data) {
                    console.log(data);
                    window.location.href = '/place/' + data;
                }
            });

        }

        function deleteComment(commentId) {

            $.ajax({
                type: "DELETE",
                url: '/api/comments/' + commentId,
                success: function(data) {
                    console.log(data);

                    if (data == "401") {
                        console.log("unauthorized");
                    } else {
                        document.getElementById('comment' + commentId).style.display = "none";
                    }
                }
            });

        }

        function deletePhoto(photoId) {

            $.ajax({
                type: "DELETE",
                url: '/api/image/' + photoId,
                success: function(data) {
                    console.log(data);

                    if (data == "401") {
                        console.log("unauthorized");
                    } else {
                        location.reload();
                    }
                }
            });

        }

        function deletePlace(id) {

            console.log(id);
            $.ajax({
                type: "DELETE",
                url: '/api/place/' + id,
                success: function(data) {
                    console.log(data);
                    if (data == "401") {
                        console.log("unauthorized");
                    } else {
                        window.location.href = "/";
                    }

                }
            });

        }
    </script>
@endsection
