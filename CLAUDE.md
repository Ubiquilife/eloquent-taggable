# CLAUDE.md — eloquent-taggable

## Overview

Ubiquilife fork of Cviebrock's Eloquent Taggable. Provides polymorphic tagging for any Eloquent model via a `Taggable` trait. Used by Appbase's QoModel to give every feature automatic tagging support.

## Namespace

`Cviebrock\EloquentTaggable`

## Key Classes

- **`Taggable`** — Trait for models. Adds `tag()`, `untag()`, `retag()`, `tags()` (MorphToMany), query scopes: `withAllTags()`, `withAnyTags()`, `withoutTags()`, `isTagged()`.
- **`Tag`** (`Models/`) — Eloquent model for the `taggable_tags` table. Has `name` and `normalized` fields.
- **`TagService`** (`Services/`) — Normalisation, parsing, CRUD operations on tags.
- **`ServiceProvider`** — Auto-discovered. Publishes config and migration.
- **Events** — `ModelTagged`, `ModelUntagged` fired on tag changes.
- **`NoTagsSpecifiedException`** — Thrown when tag operations receive empty input.

## Configuration

Tag normalisation, delimiter, and model class in `config/taggable.php`.

## Testing

```bash
cd eloquent-taggable && vendor/bin/pest
```

## Mandatory Rules

- This is a PRIVATE Ubiquilife package. Changes affect ALL apps.
- NEVER change the `Taggable` trait API or the `taggable_tags` / `taggable_taggables` table schema without checking all consuming apps.
- NEVER change tag normalisation logic — existing tags in production depend on consistent normalisation.
- Use British spelling in all text and comments.
- Test before committing.
- One logical change per commit.
