/**
 * Interaction Object module
 */
/**
 * ============================================================================
 * IMPORTS
 * ============================================================================
 * @hidden
 */
import { IInteractionObjectEvents, InteractionObjectEventDispatcher } from "./InteractionObjectEvents";
import { BaseObjectEvents } from "../Base";
import { Sprite } from "../Sprite";
import { List } from "../utils/List";
import { Dictionary } from "../utils/Dictionary";
import { AMEvent } from "../utils/EventDispatcher";
import { IPoint } from "../defs/IPoint";
import { IPointer } from "./Pointer";
import { IInertiaOptions, ISwipeOptions, IHitOptions, IHoverOptions, ICursorOptions, IKeyboardOptions } from "./InteractionOptions";
import { Inertia, InertiaTypes } from "./Inertia";
import { IDisposer } from "../utils/Disposer";
import { Optional } from "../utils/Type";
import * as $type from "../utils/Type";
/**
 * Re-exports
 */
export { IInteractionObjectEvents, InteractionObjectEventDispatcher };
/**
 * Interaction object represents an object that is subject for any kind of
 * interaction with it with any input devices: mouse, touch or keyboard.
 *
 * Any DOM element can be wrapped into an Internaction object which in turn
 * enables attaching various interaction events to it, such as: hit, drag,
 * swipe, etc.
 *
 * To create an [[InteractionObject]] out of a [[Sprite]], use:
 * `interaction.getInteractionFromSprite(sprite: Sprite)`
 *
 * To create an [[InteractionObject]] out of a a regular element:
 * `interaction.getInteraction(element: HTMLElement)`
 */
export declare class InteractionObject extends BaseObjectEvents {
    /**
     * Defines available events.
     */
    _events: IInteractionObjectEvents;
    /**
     * An [[EventDispatcher]] instance which holds events for this object
     */
    events: InteractionObjectEventDispatcher<AMEvent<this, this["_events"]>>;
    /**
     * A related [[Sprite]] if any.
     */
    sprite: Sprite;
    /**
     * Collection of Disposers for various events. (so that those get disposed
     * when the whole InteractionObject is disposed)
     *
     * @ignore Exclude from docs
     */
    eventDisposers: Dictionary<string, IDisposer>;
    /**
     * A [[Dictionary]] that holds temporarily replaced original style values for
     * HTML element, so that they can be restored when the functionality that
     * replaced them is done.
     *
     * @ignore Exclude from docs
     */
    replacedStyles: Dictionary<string, string>;
    protected _clickable: boolean;
    protected _contextMenuDisabled: boolean;
    protected _hoverable: boolean;
    protected _trackable: boolean;
    protected _draggable: boolean;
    protected _swipeable: boolean;
    protected _resizable: boolean;
    protected _wheelable: boolean;
    protected _inert: boolean;
    protected _focusable: $type.Optional<boolean>;
    protected _tabindex: Optional<number>;
    /**
     * Element to attach events to.
     */
    private _element;
    /**
     * Original coordinates for the [[InteractionObject]]. (before application
     * of the drag)
     */
    _originalPosition: Optional<IPoint>;
    /**
     * Original angle for the [[InteractionObject]]. (before rotation started)
     */
    _originalAngle: $type.Optional<number>;
    /**
     * Original scale of the [[InteractionObject]]. (before resizing started)
     */
    _originalScale: $type.Optional<number>;
    /**
     * List of pointers current over element.
     */
    protected _overPointers: $type.Optional<List<IPointer>>;
    /**
     * List of pointer currently pressing down on element.
     */
    protected _downPointers: $type.Optional<List<IPointer>>;
    /**
     * Is element currently hovered?
     */
    protected _isHover: boolean;
    /**
     * Is the element hovered by touch pointer?
     */
    protected _isHoverByTouch: boolean;
    /**
     * Has element got any pointers currently pressing down on it?
     */
    protected _isDown: boolean;
    /**
     * Does element have focus?
     */
    protected _isFocused: boolean;
    /**
     * Is element currently protected from touch interactions?
     */
    protected _isTouchProtected: boolean;
    /**
     * A timestamp of the last hit.
     *
     * Used to calculate double-hit.
     *
     * @ignore Exclude from docs
     */
    lastHit: $type.Optional<number>;
    /**
     * A pointer element that was used for the last hit.
     *
     * We need to keep this since only the same pointer can generate doublehit.
     *
     * @ignore Exclude from docs
     * @todo still needed?
     */
    lastHitPointer: $type.Optional<IPointer>;
    /**
     * Indicates whether object has delayed events initiated by touch.
     *
     * @ignore Exclude from docs
     */
    hasDelayedOut?: boolean;
    /**
     * Options used for inertia functionality.
     */
    private _inertiaOptions;
    /**
     * A collection of different inertia types, currently playing out.
     *
     * @ignore Exclude from docs
     */
    inertias: Dictionary<InertiaTypes, Inertia>;
    /**
     * Click/tap options.
     */
    private _hitOptions;
    /**
     * Hover options.
     */
    private _hoverOptions;
    /**
     * Swipe gesture options.
     */
    private _swipeOptions;
    /**
     * Keyboard options.
     */
    private _keyboardOptions;
    /**
     * Cursor options.
     */
    private _cursorOptions;
    /**
     * Constructor
     */
    constructor(element: HTMLElement | SVGSVGElement);
    /**
     * @return Hovered?
     */
    /**
     * Indicates if this element is currently hovered.
     *
     * @param value Hovered?
     */
    isHover: boolean;
    /**
     * @return Hovered?
     */
    /**
     * Indicates if this element is currently hovered.
     *
     * @param value Hovered?
     */
    isHoverByTouch: boolean;
    /**
     * A list of pointers currently over the element.
     *
     * @see {@link Pointer}
     * @return List if pointers currently hovering the element
     */
    readonly overPointers: List<IPointer>;
    /**
     * @return Has down pointers?
     */
    /**
     * Indicates if this element has currently any pointers pressing on it.
     *
     * @param value Has down pointers?
     */
    isDown: boolean;
    /**
     * A list of pointers currently pressing down on this element.
     *
     * @see {@link Pointer}
     * @return List of down pointers
     */
    readonly downPointers: List<IPointer>;
    /**
     * @return Focused?
     */
    /**
     * Indicates if this element is currently focused.
     *
     * @param value Focused?
     */
    isFocused: boolean;
    /**
     * @ignore
     * @return Touch protected?
     */
    /**
     * Indicates if this element is currently being protected from touch actions.
     *
     * @ignore
     * @param value Touch protected?
     */
    isTouchProtected: boolean;
    /**
     * @return Clickable?
     */
    /**
     * Is element clickable? Clickable elements will generate "hit" events when
     * clicked or tapped.
     *
     * @param value Clickable?
     */
    clickable: boolean;
    /**
     * @return Context menu disabled?
     */
    /**
     * Should element prevent context menu to be displayed, e.g. when
     * right-clicked?
     *
     * @default false
     * @param value Context menu disabled?
     */
    contextMenuDisabled: boolean;
    /**
     * @return Hoverable?
     */
    /**
     * Indicates if element should generate hover events.
     *
     * @param value Hoverable?
     */
    hoverable: boolean;
    /**
     * @return Track pointer?
     */
    /**
     * Indicates if pointer movement over element should be tracked.
     *
     * @param value Track pointer?
     */
    trackable: boolean;
    /**
     * @return Draggable?
     */
    /**
     * Indicates if element can be dragged. (moved)
     *
     * @param value Draggable?
     */
    draggable: boolean;
    /**
     * @return Track swipe?
     */
    /**
     * Indicates whether element should react to swipe gesture.
     *
     * @param value Track swipe?
     */
    swipeable: boolean;
    /**
     * @return Resizeble?
     */
    /**
     * Indicates if element can be resized.
     *
     * @param value Resizeable?
     */
    resizable: boolean;
    /**
     * @return Track wheel?
     */
    /**
     * Indicates whether track moouse wheel rotation over element.
     *
     * @param value Track wheel?
     */
    wheelable: boolean;
    /**
     * @return Inert?
     */
    /**
     * Indicates if element is inert, i.e. if it should carry movement momentum
     * after it is dragged and released.
     *
     * @param value Inert?
     */
    inert: boolean;
    /**
     * @return Focusable?
     */
    /**
     * Indicates if element can gain focus.
     *
     * @param value Focusable?
     */
    focusable: $type.Optional<boolean>;
    /**
     * @return Tab index
     */
    /**
     * Element's tab index.
     *
     * @param value Tab index
     */
    tabindex: number;
    /**
     * @return Element
     */
    /**
     * A DOM element associated with this element.
     *
     * @param element Element
     */
    element: HTMLElement | SVGSVGElement;
    /**
     * @ignore Exclude from docs
     * @return Position.
     */
    /**
     * Element's original position.
     *
     * @ignore Exclude from docs
     * @param value Position
     */
    originalPosition: Optional<IPoint>;
    /**
     * @return Scale
     */
    /**
     * Element's original scale.
     *
     * @ignore Exclude from docs
     * @param value Scale
     */
    originalScale: number;
    /**
     * @return Angle
     */
    /**
     * Element's original angle.
     *
     * @ignore Exclude from docs
     * @param value Angle
     */
    originalAngle: number;
    /**
     * @return Options
     */
    /**
     * Inertia options.
     *
     * @param value  Options
     */
    inertiaOptions: Dictionary<InertiaTypes, IInertiaOptions>;
    /**
     * @return Options
     */
    /**
     * Hit options.
     *
     * @param value  Options
     */
    hitOptions: IHitOptions;
    /**
     * @return Options
     */
    /**
     * Hover options.
     *
     * @param value  Options
     */
    hoverOptions: IHoverOptions;
    /**
     * @return Options
     */
    /**
     * Swipe options.
     *
     * @param value  Options
     */
    swipeOptions: ISwipeOptions;
    /**
     * @return Options
     */
    /**
     * Keyboard options.
     *
     * @param value  Options
     */
    keyboardOptions: IKeyboardOptions;
    /**
     * @return Options
     */
    /**
     * Cursor options.
     *
     * @param value  Options
     */
    cursorOptions: ICursorOptions;
    /**
     * Copies all properties and related assets from another object of the same
     * type.
     *
     * @param source Source object
     */
    copyFrom(source: this): void;
    /**
     * @ignore Exclude from docs
     */
    setEventDisposer(key: string, value: boolean, f: () => IDisposer): void;
    /**
     * Disposes object.
     */
    dispose(): void;
}
