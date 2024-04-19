<script setup>
import { onMounted, onUnmounted, ref } from 'vue';
import Phaser from 'phaser';
import Hero from '../Pics/TheHero.png';
import { Head, usePage } from '@inertiajs/vue3';




const page = usePage();
const user = page.props.auth.user;


const game = ref(null);

class Player {
  constructor(scene, x, y) {
    this.scene = scene;
    this.sprite = scene.physics.add.sprite(x, y, 'player');
    this.sprite.setCollideWorldBounds(true);
  }

  update(cursors) {
    // console.log(cursors)
    if (cursors.left.isDown) {
      this.sprite.setVelocityX(-160);
      console.log( { id: page.props.auth.user.id, x: this.sprite.x, y: this.sprite.y })
      Echo.private(`player-channel`)
        .whisper('move', {
          name: user.name,
          position: { id: page.props.auth.user.id, x: this.sprite.x, y: this.sprite.y }
        });
    } else if (cursors.right.isDown) {
      this.sprite.setVelocityX(160);
      Echo.private(`player-channel`)
        .whisper('move', {
          name: user.name,
          position: { id: page.props.auth.user.id, x: this.sprite.x, y: this.sprite.y }
        });
    } else {
      this.sprite.setVelocityX(0);
     
    }

    if (cursors.up.isDown) {
      this.sprite.setVelocityY(-160);
      Echo.private(`player-channel`)
        .whisper('move', {
          name: user.name,
          position: { id: page.props.auth.user.id, x: this.sprite.x, y: this.sprite.y }
        });
    } else if (cursors.down.isDown) {
      this.sprite.setVelocityY(160);
      Echo.private(`player-channel`)
        .whisper('move', {
          name: user.name,
          position: { id: page.props.auth.user.id, x: this.sprite.x, y: this.sprite.y }
        });
    } else {
      this.sprite.setVelocityY(0);
      
    }
  }
}


class Enemy {
  constructor(scene, x, y) {
    this.scene = scene;
    this.sprite = scene.physics.add.sprite(x, y, 'enemy');
    this.sprite.setCollideWorldBounds(true);
  }

  update(x, y) 
  {
    // if(this.sprite.x != newPosition.value.x & this.sprite.y != newPosition.value.y)
    // console.log(listenForWhisper)
    console.log('hi')
    
    // console.log(listenForWhisper)
    
    this.sprite.setPosition(x, y);
    // this.sprite.y = newPosition.value.y;
    
  }
}



const config = {
    type: Phaser.AUTO,
    width: 800,
    height: 600,
    parent: 'phaser-game',
    physics: {
      default: 'arcade',
      arcade: {
        gravity: { y: 0 },
        debug: false,
        overlapOnly: true
      }
    },
    scene: {
      preload() {
        this.load.image('player', Hero);
        this.load.image('enemy', Hero);
      },
      create() {
        const cursors = this.input.keyboard.createCursorKeys();
       
        const player = new Player(this, 100, 100);
        const enemy = new Enemy(this, 400, 300);
        Echo.private(`player-channel`)
        .listenForWhisper('move', (e) => {
            // Directly update enemy's position based on the whisper event data
            if (enemy) {
                enemy.update(e.position.x, e.position.y);
            }
        });

        this.physics.add.collider(player.sprite, enemy.sprite);

        this.update = function () {
          player.update(cursors);
        //   player.update(x, y);
        };
      }
    }
};



Echo.private(`player-channel`)
  .listenForWhisper('move', (e) => {
    // console.log(Echo)
//    game.value.config.scene.enemy.update(e.position.x, e.position.y);
  });


onMounted(() => {
    game.value = new Phaser.Game(config);
});
// console.log(game.value)


onUnmounted(() => {
    game.value.destroy(true);
});
</script>

<template>
  <div id="phaser-game"></div>
</template>

<style>
#phaser-game {
  width: 800px;
  height: 600px;
  margin: auto;
}
</style>
