<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>My first three.js app</title>
    <style>
    body {
        margin: 0;
    }

    canvas {
        display: block;
    }
    </style>
</head>

<body>
    <script src="js/three.js"></script>
    <script src="GLTFLoader.js"></script>
    <script src="OrbitControls.js"></script>
    <script src='js/OBJLoader.js'></script>
    <script src='js/MTLLoader.js'></script>
    <script src='js/DDSLoader.js'></script>
    <script src='js/TrackballControls.js'></script>

    <script>
    var container;

    var camera, scene, renderer;

    var mouseX = 0,
        mouseY = 0;

    var windowHalfX = window.innerWidth / 2;
    var windowHalfY = window.innerHeight / 2;

    container = document.createElement('div');
    document.body.appendChild(container);

    camera = new THREE.PerspectiveCamera(45, window.innerWidth / window.innerHeight, 1, 2000);
    camera.position.z = 450;


    // scene

    scene = new THREE.Scene();

    var ambientLight = new THREE.AmbientLight(0xcccccc, 0.4);
    scene.add(ambientLight);

    var pointLight = new THREE.PointLight(0xffffff, 0.8);
    camera.add(pointLight);
    scene.add(camera);

    // model

    var onProgress = function(xhr) {

        if (xhr.lengthComputable) {

            var percentComplete = xhr.loaded / xhr.total * 100;
            console.log(Math.round(percentComplete, 2) + '% downloaded');

        }

    };

    var onError = function() {};

    var manager = new THREE.LoadingManager();
    manager.addHandler(/\.dds$/i, new THREE.DDSLoader());

    // comment in the following line and import TGALoader if your asset uses TGA textures
    // manager.addHandler( /\.tga$/i, new TGALoader() );

    new THREE.MTLLoader(manager)
        .setPath('/male02/')
        .load('male02_dds.mtl', function(materials) {

            materials.preload();

            new THREE.OBJLoader(manager)
                .setMaterials(materials)
                .setPath('/male02/')
                .load('male02.obj', function(object) {

                    object.position.y = -95;
                    scene.add(object);

                }, onProgress, onError);

        });

    //

    renderer = new THREE.WebGLRenderer();
    renderer.setPixelRatio(window.devicePixelRatio);
    renderer.setSize(window.innerWidth, window.innerHeight);
    container.appendChild(renderer.domElement);

    /* document.addEventListener('mousemove', onDocumentMouseMove, false);

     //

     window.addEventListener('resize', onWindowResize, false);*/

    let controls = new THREE.OrbitControls(camera, renderer.domElement);


    /*
        function onWindowResize() {

            windowHalfX = window.innerWidth / 2;
            windowHalfY = window.innerHeight / 2;

            camera.aspect = window.innerWidth / window.innerHeight;
            camera.updateProjectionMatrix();

            renderer.setSize(window.innerWidth, window.innerHeight);

        }

        /*  function onDocumentMouseMove(event) {

            mouseX = (event.clientX - windowHalfX) / 2;
            mouseY = (event.clientY - windowHalfY) / 2;

        }
    */
    //

    var texture = new THREE.TextureLoader().load("https://threejsfundamentals.org/threejs/resources/images/wall.jpg");

    var geometry = new THREE.PlaneBufferGeometry(80, 44, 32);
    var material = new THREE.MeshBasicMaterial({
        map: texture,
        side: THREE.DoubleSide
    });
    var plane = new THREE.Mesh(geometry, material);
    plane.position.x = -150;
    plane.rotation.y = 1.5;
    scene.add(plane);

    var angle = 0;

    function render() {
        camera.lookAt(scene.position);

        controls.update();

        renderer.render(scene, camera);
    }

    function animate() {
        plane.position.x += 2.5 * Math.sin(angle);
        plane.position.z += 2.5 * Math.cos(angle);
        plane.rotation.y += Math.PI / 180;

        //plane.rotation.y += Math.PI / 180;
        angle += Math.PI / 180;
        requestAnimationFrame(animate);
        render();

    }

    animate();
    </script>
</body>

</html>