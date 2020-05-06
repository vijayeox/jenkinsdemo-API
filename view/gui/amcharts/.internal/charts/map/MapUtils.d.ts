/**
 * A collection of Map-related utility functions.
 */
/**
 * ============================================================================
 * IMPORTS
 * ============================================================================
 * @hidden
 */
import { IGeoPoint } from "../../core/defs/IGeoPoint";
/**
 * Converts a multi-part polygon in X/Y coordinates to a geo-multipolygon in
 * geo-points (lat/long).
 *
 * @param multiPolygon  Source multi-polygon
 * @return Geo-multipolygon
 */
export declare function multiPolygonToGeo(multiPolygon: number[][][][]): IGeoPoint[][][];
/**
 * Converts a multiline in X/Y coordinates to a geo-multiline in geo-points
 * (lat/long).
 *
 * @param multiLine  Source multiline
 * @return Geo-multiline
 */
export declare function multiLineToGeo(multiLine: number[][][]): IGeoPoint[][];
/**
 * Converts multiple X/Y points into a lat/long geo-points.
 *
 * @param points  Source points
 * @return Geo-points
 */
export declare function multiPointToGeo(points: number[][]): IGeoPoint[];
/**
 * Converts multiple X/Y points into a lat/long geo-points.
 *
 * @param points  Source points
 * @return Geo-points
 */
export declare function multiGeoToPoint(geoPoints: IGeoPoint[]): number[][];
/**
 * Converts X/Y point into a lat/long geo-point.
 *
 * @param point  Source point
 * @return Geo-point
 */
export declare function pointToGeo(point: number[]): IGeoPoint;
/**
 * Converts geo line (collection of lat/long coordinates) to screen line (x/y).
 *
 * @param   multiGeoLine  Source geo line
 * @return                Screen line
 */
export declare function multiGeoLineToMultiLine(multiGeoLine: IGeoPoint[][]): number[][][];
/**
 * Converts a geo polygon (collection of lat/long coordinates) to screen
 * polygon (x/y).
 *
 * @param   multiGeoPolygon  Source polygon
 * @return                   Screen polygon
 */
export declare function multiGeoPolygonToMultipolygon(multiGeoPolygon: IGeoPoint[][][]): number[][][][];
/**
 * Returns a set of geographical coordinates for the circle with a center
 * at specific lat/long coordinates and radius (in degrees).
 *
 * @since 4.3.0
 * @param   longitude  Center longitude
 * @param   latitude   Center latitude
 * @param   radius     Radius (degrees)
 * @return             Circle coordinates
 */
export declare function getCircle(longitude: number, latitude: number, radius: number): number[][][][];
/**
 * Returns a set of screen coordinates that represents a "background" area
 * between provided extremities.
 *
 * @since 4.3.0
 * @param   north  North latitude
 * @param   east   East longitude
 * @param   south  South latitude
 * @param   west   West longitude
 * @return         Polygon
 */
export declare function getBackground(north: number, east: number, south: number, west: number): number[][][][];
