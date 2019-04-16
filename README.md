# Borizqy Dummy Image

An image placeholder to get attention that this image need to be replaced. You can make an image by using basic CSS experience.

## How to install?

We recommend you to install this library by using composer. But, if you don't use composer you can still install it manually by downloading it.

### 1. Via Composer

1. Get on your terminal and change directory to your project, then type this:  
   ```
   composer require fikoborizqy/dummy-image
   ```  
2. Once Process succeed, create a new blank file `example.php`. You can change the name to anything.
3. Copy this basic code:  
   ```
   Borizqy\DummyImage\DummyImage::create([
   	"height" 		=> "128px",
   	"width" 		=> "128px",
   	"font-size" 		=> "48pt",
   	"background-color" 	=> "#8EC051",
   	"color" 		=> "#709c3d",
   	"content" 		=> "FB",
   	"font-family" 		=> "roboto"
   ]);
   ```
   
### 2. Manually

1. `Download ZIP` files  
   ![Gambar](https://i.ibb.co/r4HCscw/image.png)
2. Extract that zip to your project directory.
3. Copy `example.php` from Dummy Image directory to you project.
4. Access that example.php on browser. (You can rename that file or change the CSS)

## Contributing

Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License

[MIT](https://choosealicense.com/licenses/mit/) &copy; 2019