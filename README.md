# developer-test
 Course achievement and badges Api

## Installation

Clone or Download Repository
```
git clone https://github.com/somuoki/developer-test.git
cd developer-test
composer install
```

## Endpoint
```
users/{user}/achievements
```

Returns 
```
{
    "unlocked_achievements":[All Achievements strings],
    "next_available_achievements":[Immediate next Achievements],
    "current_badge":"Current Badge",
    "next_badge":"Next Badge",
    "remaining_to_unlock_next_badge": No of Achievements left to get to next
 }
 ```

