<template>
  <div>
    <!-- Hero Section -->
    <section class="">
      <div class="container">
        <!-- Hero Text -->
        <div class="hero-text">
          <p class=" text-green-700 callibari text-7xl">99% Healthiest</p>
          <p class=" text-gray-400 text-5xl">Home made Recipes</p>
          <p class="subheadline">with only FoodieHub!</p>
          <router-link to="/recipes">
          <button class="cta-button bg-green-700 text-white mt-4 rounded-md">Check our recipes Now</button>
        </router-link>
      </div>
        <!-- Three.js Canvas Section -->
        <div class="canvas-container">
          <canvas ref="canvas" class="webgl"></canvas>
          <h1 id="ld">Loading ...</h1>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
import * as THREE from 'three';
import { OrbitControls } from 'three/examples/jsm/controls/OrbitControls.js';
import { GLTFLoader } from 'three/examples/jsm/loaders/GLTFLoader.js';

export default {
  name: 'HeroSection',
  mounted() {
    this.initThreeJS();
  },
  beforeDestroy() {
    this.cleanupThreeJS();
  },
  methods: {
    initThreeJS() {
      const canvas = this.$refs.canvas;
      const scene = new THREE.Scene();
      const textureLoader = new THREE.TextureLoader();

      const sizes = {
        width: canvas.parentElement.clientWidth,
        height: canvas.parentElement.clientHeight
      };

      // Base camera
      const camera = new THREE.PerspectiveCamera(10, sizes.width / sizes.height, 0.1, 1000);
      camera.position.set(50, 35, 50);
      scene.add(camera);

      // Controls
      const controls = new OrbitControls(camera, canvas);
      controls.enableDamping = true;
      controls.enableZoom = true;
      controls.enablePan = true;
      controls.minDistance = 50;
      controls.maxDistance = 80;

      // Renderer
      const renderer = new THREE.WebGLRenderer({
        canvas: canvas,
        antialias: true,
        alpha: true
      });

      renderer.setSize(sizes.width, sizes.height);
      renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
      renderer.outputEncoding = THREE.sRGBEncoding;

      // Materials
      const bakedTexture = textureLoader.load('https://rawcdn.githack.com/ricardoolivaalonso/ThreeJS-Room09/63b22b78b58af29d8b0d2ca196e241e1e8490fe7/dist/baked.jpg');
      bakedTexture.flipY = false;
      bakedTexture.encoding = THREE.sRGBEncoding;

      const bakedMaterial = new THREE.MeshBasicMaterial({
        map: bakedTexture,
      });

      const cubeMaterial = new THREE.MeshBasicMaterial({ color: 0x3c6436 });

      // Loader
      const loader = new GLTFLoader();
      loader.load('https://rawcdn.githack.com/ricardoolivaalonso/ThreeJS-Room09/2515f48ad0b678bb7e16821ffb1960e01d35c65d/dist/model.glb',
        (gltf) => {
          const model = gltf.scene;
          model.traverse(child => {
            child.material = bakedMaterial;
            if (child.name === 'Pasto') child.material = cubeMaterial;
          });
          scene.add(model);
          document.getElementById('ld').style.display = 'none';
        },
        (xhr) => {
          console.log((xhr.loaded / xhr.total * 100) + '% loaded');
        }
      );

      window.addEventListener('resize', this.onWindowResize(camera, renderer, sizes));

      // Animation
      const tick = () => {
        controls.update();
        renderer.render(scene, camera);
        this.animationId = window.requestAnimationFrame(tick);
      };

      tick();

      // Store references for cleanup
      this.three = { scene, camera, renderer, controls, loader, textureLoader, tick };
    },
    onWindowResize(camera, renderer, sizes) {
      return () => {
        sizes.width = canvas.parentElement.clientWidth;
        sizes.height = canvas.parentElement.clientHeight;
        camera.aspect = sizes.width / sizes.height;
        camera.updateProjectionMatrix();
        renderer.setSize(sizes.width, sizes.height);
        renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
      };
    },
    cleanupThreeJS() {
      const { renderer, controls, tick, scene, camera } = this.three;
      window.cancelAnimationFrame(this.animationId);
      window.removeEventListener('resize', this.onWindowResize(camera, renderer, sizes));
      
      controls.dispose();
      renderer.dispose();
      scene.traverse((object) => {
        if (!object.isMesh) return;
        object.geometry.dispose();
        if (object.material.isMaterial) {
          this.cleanMaterial(object.material);
        } else {
          for (const material of object.material) this.cleanMaterial(material);
        }
      });
    },
    cleanMaterial(material) {
      material.dispose();
      for (const key in material) {
        if (material[key] && typeof material[key].dispose === 'function') {
          material[key].dispose();
        }
      }
    }
  },
  data() {
    return {
      three: null,
      animationId: null
    };
  }
};
</script>

<style scoped>

.container {
  display: grid;
  grid-template-columns: 1fr 1fr;
  align-items: center;
  gap: 2rem;
}

.hero-text {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: flex-end;
  text-align: left;
}



.subheadline {
  margin-top: 0.5rem;
  font-size: 1.25rem;
  color: #666;
}

.cta-button {
  padding: 0.75rem 1.5rem;
  font-size: 1.25rem;
  border: none;
  cursor: pointer;
  transition: background-color 0.3s;
}

.cta-button:hover {
  background-color: #d4ac0d;
}

.canvas-container {
  position: relative;
  width: 100%;
  height: 100vh;
}

.webgl {
  width: 100%;
  height: 100%;
  display: block;
}

#ld {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  color: white;
  font-size: 1.5rem;
  font-family: monospace;
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-fade-in-up {
  animation: fadeInUp 1s ease-out;
}
</style>
