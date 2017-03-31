<?php

use Encore\Admin\Auth\Database\Administrator;
use Illuminate\Support\Facades\File;
use Tests\Models\Image;
use Tests\Models\MultipleImage;

class ImageUploadTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->be(Administrator::first(), 'admin');
    }

    public function testDisableFilter()
    {
        $this->visit('admin/images')
            ->dontSeeElement('input[name=id]');
    }

    public function testImageUploadPage()
    {
        $this->visit('admin/images/create')
            ->see('Upload image')
            ->seeInElement('h3[class=box-title]', 'Create')
            ->seeElement('input[name=image1]')
            ->seeElement('input[name=image2]')
            ->seeElement('input[name=image3]')
            ->seeElement('input[name=image4]')
            ->seeElement('input[name=image5]')
            ->seeElement('input[name=image6]')
//            ->seeInElement('a[href="/admin/images"]', 'List')
            ->seeInElement('button[type=reset]', 'Reset')
            ->seeInElement('button[type=submit]', 'Submit');
    }

    protected function uploadImages()
    {
        return $this->visit('admin/images/create')
            ->attach(__DIR__.'/assets/test.jpg', 'image1')
            ->attach(__DIR__.'/assets/test.jpg', 'image2')
            ->attach(__DIR__.'/assets/test.jpg', 'image3')
            ->attach(__DIR__.'/assets/test.jpg', 'image4')
            ->attach(__DIR__.'/assets/test.jpg', 'image5')
            ->attach(__DIR__.'/assets/test.jpg', 'image6')
            ->press('Submit');
    }

    public function testUploadImage()
    {
        File::cleanDirectory(public_path('upload/image'));

        $this->uploadImages()
            ->seePageIs('admin/images');

        $this->assertEquals(Image::count(), 1);

        $this->seeInDatabase('test_images', ['image4' => 'image/renamed.jpeg']);

        $images = Image::first()->toArray();

        foreach (range(1, 6) as $index) {
            $this->assertFileExists(public_path('upload/'.$images['image'.$index]));
        }

        $this->assertFileExists(public_path('upload/image/asdasdasdasdasd.jpeg'));

        File::cleanDirectory(public_path('upload/image'));
    }

    public function testRemoveImage()
    {
        File::cleanDirectory(public_path('upload/image'));

        $this->uploadImages();

        $this->assertEquals($this->fileCountInImageDir(), 6);

        // remove image2
//        $this->call(
//            'PUT', // $method
//            '/admin/images/1', // $action
//            ['image2' => '', '__del__' => '', 'key' => 0] // $parameters
//        );
//
//        $this->assertEquals($this->fileCountInImageDir(), 5);
//
//        // remove image5
//        $this->call(
//            'PUT', // $method
//            '/admin/images/1', // $action
//            ['image5' => '', '__del__' => '', 'key' => 0] // $parameters
//        );
//
//        $this->assertEquals($this->fileCountInImageDir(), 4);
    }

    public function testUpdateImage()
    {
        File::cleanDirectory(public_path('upload/image'));

        $this->uploadImages();

        $old = Image::first();

        $this->visit('admin/images/'.$old->id.'/edit')
            ->see('ID')
            ->see('Created At')
            ->see('Updated At')
            ->seeElement('input[name=image1]')
            ->seeElement('input[name=image2]')
            ->seeElement('input[name=image3]')
            ->seeElement('input[name=image4]')
            ->seeElement('input[name=image5]')
            ->seeElement('input[name=image6]')
//            ->seeInElement('a[href="/admin/images"]', 'List')
            ->seeInElement('button[type=reset]', 'Reset')
            ->seeInElement('button[type=submit]', 'Submit');

        $this->attach(__DIR__.'/assets/test.jpg', 'image3')
            ->attach(__DIR__.'/assets/test.jpg', 'image4')
            ->attach(__DIR__.'/assets/test.jpg', 'image5')
            ->press('Submit');

        $new = Image::first();

        $this->assertEquals($old->id, $new->id);
        $this->assertEquals($old->image1, $new->image1);
        $this->assertEquals($old->image2, $new->image2);
        $this->assertEquals($old->image6, $new->image6);

        $this->assertNotEquals($old->image3, $new->image3);
        $this->assertNotEquals($old->image4, $new->image4);
        $this->assertNotEquals($old->image5, $new->image5);

        File::cleanDirectory(public_path('upload/image'));
    }

    public function testDeleteImages()
    {
        File::cleanDirectory(public_path('upload/image'));

        $this->uploadImages();

        $image = Image::first()->toArray();

        $this->visit('admin/images')
            ->seeInElement('td', $image['id']);

        $this->delete('admin/images/'.$image['id'])
            ->dontSeeInDatabase('test_images', ['id' => $image['id']]);

        foreach (range(1, 6) as $index) {
            $this->assertFileNotExists(public_path('upload/'.$image['image'.$index]));
        }

        $this->visit('admin/images')
            ->dontSeeInElement('td', $image['id']);
    }

    public function testBatchDelete()
    {
        File::cleanDirectory(public_path('upload/image'));

        $this->uploadImages();
        $this->uploadImages();
        $this->uploadImages();

        list($id1, $id2, $id3) = Image::take(3)->pluck('id')->toArray();

        $this->visit('admin/images')
            ->seeInElement('td', $id1)
            ->seeInElement('td', $id2)
            ->seeInElement('td', $id3);

        $this->assertEquals($this->fileCountInImageDir(), 18);

        $this->assertEquals(Image::count(), 3);

        $this->delete(sprintf('admin/images/%d,%d,%d', $id1, $id2, $id3));

        $this->assertEquals(Image::count(), 0);

        $this->visit('admin/images')
            ->dontSeeInElement('td', $id1)
            ->dontSeeInElement('td', $id2)
            ->dontSeeInElement('td', $id3);

        $this->assertEquals($this->fileCountInImageDir(), 0);
    }

    public function testUploadMultipleImage()
    {
        File::cleanDirectory(public_path('upload/image'));

        $this->visit('admin/multiple-images/create')
            ->seeElement('input[type=file][name="pictures[]"][multiple=1]');

        $path = __DIR__.'/assets/test.jpg';

        $file = new \Illuminate\Http\UploadedFile(
            $path, 'test.jpg', 'image/jpeg', filesize($path), null, true
        );

        $size = rand(10, 20);
        $files = ['pictures' => array_pad([], $size, $file)];

        $this->call(
            'POST', // $method
            '/admin/multiple-images', // $action
            [], // $parameters
            [],
            $files
        );

        $this->assertResponseStatus(302);
        $this->assertRedirectedTo('/admin/multiple-images');

        $this->assertEquals($this->fileCountInImageDir(), $size);

        $pictures = MultipleImage::first()->pictures;

        $this->assertCount($size, $pictures);

        foreach ($pictures as $picture) {
            $this->assertFileExists(public_path('upload/'.$picture));
        }
    }

    public function testRemoveMultipleFiles()
    {
        File::cleanDirectory(public_path('upload/image'));

        // upload files
        $path = __DIR__.'/assets/test.jpg';

        $file = new \Illuminate\Http\UploadedFile(
            $path, 'test.jpg', 'image/jpeg', filesize($path), null, true
        );

        $size = rand(10, 20);
        $files = ['pictures' => array_pad([], $size, $file)];

        $this->call(
            'POST', // $method
            '/admin/multiple-images', // $action
            [], // $parameters
            [],
            $files
        );

        $this->assertEquals($this->fileCountInImageDir(), $size);

//        // remove files
//        $this->call(
//            'PUT', // $method
//            '/admin/multiple-images/1', // $action
//            ['pictures' => '', '__del__' => '', 'key' => 0] // $parameters
//        );
//
//        $this->assertEquals($this->fileCountInImageDir(), $size - 1);
    }

    protected function fileCountInImageDir($dir = 'upload/image')
    {
        $file = new FilesystemIterator(public_path($dir), FilesystemIterator::SKIP_DOTS);

        return iterator_count($file);
    }
}
