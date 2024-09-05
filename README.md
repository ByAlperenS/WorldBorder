# World Border

<a href="https://poggit.pmmp.io/p/WorldBorders"><img src="https://poggit.pmmp.io/shield.state/WorldBorders"></a>
<br>
This plugin adds a new border block to your server and covers an area you specify with border blocks.

![](https://github.com/ByAlperenS/WorldBorder/blob/master/image/image.png)

> [!WARNING]
> Please install the resource pack for the plugin to work properly

## Table of Contents

- [Features](#features)
- [Commands](#commands)
- [Required](#required)
- [Pack](#pack)
- [API](#api)

## Features

- **Set and Manage Borders:** Define and manage world borders with ease.
- **Performance Optimization:** Processes blocks as fast as possible.
- **Database (SQLite):** It stores data faster and more securely.

## Commands

- **/wborder help:** List commands.
- **/wborder create [name]:** Creates a area named **_name_**
- **/wborder delete [name]:** Deletes a area named **_name_**
- **/wborder list:** Lists available areas

## Required

- [Customies](https://github.com/CustomiesDevs/Customies)    (For add new border block)
- [LibraSQL-API](https://github.com/LibraNetwork/LibraSQL-API)    (For easy to use sqlite database)

## Pack

[Download](https://github.com/ByAlperenS/WorldBorder/blob/master/pack/WorldBorder.zip)

## API

#### Create Border:
```php
AreaManager::addArea((string) borderName, (World) world, (Vector3) pos1, (Vector3) pos2);
(AreaManager::getArea((string) borderName))->build();
```

#### Delete Border:
```php
(AreaManager::getArea((string) borderName))->delete();
AreaManager::deleteArea((string) borderName);
```
##
> [!IMPORTANT]
> Please [report](https://github.com/ByAlperenS/WorldBorder/issues) questions, errors and omissions.
