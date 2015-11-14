---
title: Parser Change Log

---

## Version 3.1 {#v3-1}

### v3.1.3 {#v3-1-3}

* Add `paragonie/random_compat` as dependencies.

### v3.1.2 {#v3-1-2}

* Introduce `Orchestra\Parser\Xml\Document::rebase()` in order to change the parent node for faster parsing.
* Introduce `Orchestra\Parser\Xml\Document::namespaced()` to fetch namespaced elements.
* Add support to access namespaced elements via collection by using `item/ns[value1,value2]` etc.

### v3.1.1 {#v3-1-1}

* Add functionality to swap attribute key with an alias using `item::attribute>attribute`.
* Add functionality to parse multiple same-name same-level elements with properties using `property(::id,value)`.

### v3.1.0 {#v3-1-0}

* Update support to Laravel Framework v5.1.

## Version 3.0 {#v3-0}

### v3.0.2 {#v3-0-2}

* Introduce `Orchestra\Parser\Xml\Document::rebase()` in order to change the parent node for faster parsing.
* Introduce `Orchestra\Parser\Xml\Document::namespaced()` to fetch namespaced elements.
* Add support to access namespaced elements via collection by using `item/ns[value1,value2]` etc.

### v3.0.1 {#v3-0-1}

* Add functionality to swap attribute key with an alias using `item::attribute>attribute`.
* Add functionality to parse multiple same-name same-level elements with properties using `property(::id,value)`.

### v3.0.0 {#v3-0-0}

* Update support to Laravel Framework v5.0.
* Simplify PSR-4 path.

## Version 2.2 {#v2-2}

### v2.2.1@dev {#v2-2-1}

* Utilize `Illuminate\Support\Arr`.

### v2.2.0 {#v2-2-0}

* Bump minimum version to PHP v5.4.0.

## Version 2.1 {#v2-1}

### v2.1.0 {#v2-1-0}

* Initial version.

