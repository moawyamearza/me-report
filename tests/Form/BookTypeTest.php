<?php


use App\Form\BookType;
use App\Entity\Booklib;
use Symfony\Component\Form\Test\TypeTestCase;

class BookTypeTest extends TypeTestCase
{
    public function testBookTypeForm(): void
    {
        // Create a new instance of the Booklib entity
        $book = new Booklib();

        // Create the form
        $form = $this->factory->create(BookType::class, $book);

        // Create form data
        $formData = [
            'bookname' => 'Test Book',
            'isbn' => '1234567890',
            'writer' => 'Test Author',
            'image' => 'http://example.com/test-book-cover.jpg',
        ];

        // Submit the form with the test data
        $form->submit($formData);

        // Check if the form is synchronized
        $this->assertTrue($form->isSynchronized());

        // Check if the form data matches the entity data
        $this->assertSame('Test Book', $book->getBookname());
        $this->assertSame('1234567890', $book->getIsbn());
        $this->assertSame('Test Author', $book->getWriter());
        $this->assertSame('http://example.com/test-book-cover.jpg', $book->getImage());

        // Create the form view and check the form fields
        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }

        // Check the field labels
        $this->assertEquals('Book Name', $children['bookname']->vars['label']);
        $this->assertEquals('ISBN', $children['isbn']->vars['label']);
        $this->assertEquals('Author', $children['writer']->vars['label']);
        $this->assertEquals('Book Cover', $children['image']->vars['label']);
    }
}
