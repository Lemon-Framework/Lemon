# Lemon Tree

*For better expirience its recommended to play [this track](https://www.youtube.com/watch?v=7m9ivNr-HLE)*

## Concept

Model describes table. If you run `php lemonade migrate` it creates table by model definition:

```php
#[Table('users')]
class User extends \Lemon\Database\Tree\Model
{
    #[AutoIncrement()]
    public int $id;
    public string $name;
    public string $password;
    #[BelongsTo(Group::class)]
    public int $group_id;
    public \DateTime $created_at;
}
```

This basicaly combines laravel's migrations with eloquent.

If you change the model it will ask you how you want to change your table.

Models have some nice stuff for changing them you know.

So we need query builder


