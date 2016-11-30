<?php

use Encore\Admin\Auth\Database\Administrator;
use Illuminate\Support\Facades\File;
use Tests\Models\Image;

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
            ->see('ID')
            ->see('Created At')
            ->see('Updated At')
            ->seeElement('input[name=image1]')
            ->seeElement('input[name=image2]')
            ->seeElement('input[name=image3]')
            ->seeElement('input[name=image4]')
            ->seeElement('input[name=image5]')
            ->seeElement('input[name=image6]')
            ->seeInElement('a[href="/admin/images"]', 'List')
            ->seeInElement('a[class*=item_delete]', 'Delete')
            ->seeElement('input[type=reset][value=Reset]')
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

        File::cleanDirectory(public_path('upload/image'));
    }

    public function testUpdateImage()
    {
        File::cleanDirectory(public_path('upload/image'));

        $this->uploadImages();

        $old = Image::first();

        $this->visit('admin/images/1/edit')
            ->see('ID')
            ->see('Created At')
            ->see('Updated At')
            ->seeElement('input[name=image1]')
            ->seeElement('input[name=image2]')
            ->seeElement('input[name=image3]')
            ->seeElement('input[name=image4]')
            ->seeElement('input[name=image5]')
            ->seeElement('input[name=image6]')
            ->seeInElement('a[href="/admin/images"]', 'List')
            ->seeInElement('a[class*=item_delete]', 'Delete')
            ->seeElement('input[type=reset][value=Reset]')
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

        $this->visit('admin/images')
            ->seeInElement('td', 1);

        $images = Image::first()->toArray();

        $this->delete('admin/images/1')
            ->dontSeeInDatabase('test_images', ['id' => 1]);

        foreach (range(1, 6) as $index) {
            $this->assertFileNotExists(public_path('upload/'.$images['image'.$index]));
        }

        $this->visit('admin/images')
            ->dontSeeInElement('td', 1);
    }

    public function testBatchDelete()
    {
        File::cleanDirectory(public_path('upload/image'));

        $this->uploadImages();
        $this->uploadImages();
        $this->uploadImages();

        $this->visit('admin/images')
            ->seeInElement('td', 1)
            ->seeInElement('td', 2)
            ->seeInElement('td', 3);

        $fi = new FilesystemIterator(public_path('upload/image'), FilesystemIterator::SKIP_DOTS);

        $this->assertEquals(iterator_count($fi), 18);

        $this->assertEquals(Image::count(), 3);

        $this->delete('admin/images/1,2,3');

        $this->assertEquals(Image::count(), 0);

        $this->visit('admin/images')
            ->dontSeeInElement('td', 1)
            ->dontSeeInElement('td', 2)
            ->dontSeeInElement('td', 3);

        $this->assertEquals(iterator_count($fi), 0);
    }
}
